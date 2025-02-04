<?php
session_start();
//DB connection
include_once('includes/config.php');

//validating Session
if (strlen($_SESSION['aid'] == 0)) {
    header('location:logout.php');
} else {

    // Check if the necessary POST data is sent by Arduino
    if (isset($_POST['systolic']) && isset($_POST['diastolic']) && isset($_POST['pulse'])) {
        // Get the POST values
        $userid = $_SESSION['aid'];
        $memberid = $_POST['memberid'];  // Ensure Arduino sends 'memberid'
        $sys = $_POST['systolic'];
        $dia = $_POST['diastolic'];
        $pulse = $_POST['pulse'];
        $bpdt = $_POST['bpdatetime'];  // Ensure Arduino sends 'bpdatetime'

        // Determine if the heart rate is abnormal
        $isAbnormalHeartRate = 0; // Default to normal
        if ($pulse < 60 || $pulse > 100) { // Abnormal heart rate condition
            $isAbnormalHeartRate = 1;
        }

        // Insert data into the database, including the abnormal heart rate flag
        $query = "INSERT INTO tblbpdetails(userId, memberId, systolic, diastolic, pulse, bpDateTime, isAbnormalHeartRate) 
                  VALUES('$userid', '$memberid', '$sys', '$dia', '$pulse', '$bpdt', '$isAbnormalHeartRate')";

        $result = mysqli_query($con, $query);

        if ($result) {
            echo '<script>alert("BP details added successfully.")</script>';
            echo "<script>window.location.href='manage-bp-details.php'</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
            echo "<script>window.location.href='add-phlebotomist.php'</script>";
        }
    } else {
        echo "<script>alert('Required data not received.');</script>";
        echo "<script>window.location.href='add-phlebotomist.php'</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BP Monitoring Management System | Add Family Member BP Details</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style type="text/css">
        label {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include_once('includes/sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include_once('includes/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Add Family Member BP Details</h1>
                    <form name="addphlebotomist" method="post">
                        <div class="row">

                            <div class="col-lg-8">

                                <!-- Basic Card Example -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">BP Details Information</h6>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Select Family Member</label>
                                            <select class="form-control" id="memberid" name="memberid" required="true">
                                                <option value="">Select</option>
                                                <?php
                                                $uid = $_SESSION['aid'];
                                                $query = mysqli_query($con, "select * from tblfamilymembers where userId='$uid'");
                                                while ($row = mysqli_fetch_array($query)) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['memberName']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>SYS mmHg</label>
                                            <input type="text" class="form-control" id="systolic" name="systolic" placeholder="Systolic blood pressure" maxlength="3" required="true">
                                        </div>

                                        <div class="form-group">
                                            <label>DIA mmHg</label>
                                            <input type="text" class="form-control" id="diastolic" name="diastolic" placeholder="Diastolic blood pressure" maxlength="3" required="true">
                                        </div>

                                        <div class="form-group">
                                            <label>Pulses /min</label>
                                            <input type="text" class="form-control" id="pulses" name="pulses" placeholder="Enter pulses per minute" maxlength="3" required="true">
                                        </div>

                                        <div class="form-group">
                                            <label>Date and Time</label>
                                            <input type="datetime-local" class="form-control" id="bpdatetime" name="bpdatetime" required="true">
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary btn-user btn-block" name="submit" id="submit">
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </form>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include_once('includes/footer.php'); ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <?php include_once('includes/footer2.php'); ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>
</html>
<?php  ?>
