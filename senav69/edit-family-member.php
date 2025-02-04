<?php session_start();
//DB conncetion
include_once('includes/config.php');
//validating Session
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{


if(isset($_POST['submit'])){
//getting post values
$membername=$_POST['membername'];
$memberrelation=$_POST['memberrelation'];
$memberage=$_POST['memberage'];
$contact=$_POST['contact_number'];

$fmid=intval($_GET['fmid']);
$query="update  tblfamilymembers set memberName='$membername',memberRelation='$memberrelation',memberAge='$memberage', contact_number='$contact' where id='$fmid'";
$result =mysqli_query($con, $query);
if ($result) {
echo '<script>alert("Family member updated successfully.")</script>';
  echo "<script>window.location.href='manage-family-members.php'</script>";
} 
else {
    echo "<script>alert('Something went wrong. Please try again.');</script>";  
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

    <title>BP Monitoring  Management System | Edit Family Member</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
<style type="text/css">
label{
    font-size:16px;
    font-weight:bold;
    color:#000;
}

</style>


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

<?php include_once('includes/sidebar.php');?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
          <?php include_once('includes/topbar.php');?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Add Family Member</h1>
<form name="addphlebotomist" method="post">
  <div class="row">

                        <div class="col-lg-8">

                            <!-- Basic Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                                </div>
                                <div class="card-body">
<?php $uid=$_SESSION['aid'];
$fmid=intval($_GET['fmid']);
$query=mysqli_query($con,"select * from tblfamilymembers where id='$fmid' and userId='$uid'");
$cnt=1;
while($row=mysqli_fetch_array($query)){
?>

     <div class="form-group">
                            <label>Member Name</label>
                                    <input type="text" class="form-control" id="membername" name="membername"  placeholder="Enter Family Member Name"   required="true" value="<?php echo $row['memberName'];?>">
                                     
                                        </div>

                        <div class="form-group">
                            <label>Relation</label>
                                            <select class="form-control" id="memberrelation" name="memberrelation"  required="true">
                                                <option value="<?php echo $row['memberRelation'];?>"><?php echo $row['memberRelation'];?></option>
                                                <option value="Mother">Mother</option>
                                                <option value="Father">Father</option>
                                                <option value="Sister">Sister</option>
                                                <option value="Brother">Brother</option>
                                                <option value="Wife">Wife</option>
                                                <option value="Husband">Husband</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                             <label>Member age</label>
                            <input type="text" class="form-control" id="memberage" name="memberage" placeholder="Please enter your age" pattern="[0-9]+" title="2 numeric characters only" required="true" maxlength="2" value="<?php echo $row['memberAge'];?>" >
                                          
                                        </div>
                                        <div class="form-group">
                                             <label>Contact Number</label>
                                             <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Please enter your contact number" pattern="^(\+63|0)?(9\d{9})$" title="Enter a valid Philippine phone number starting with +63 or 09, followed by 9 digits" required maxlength="13">
                                          
                                        </div>
                        
<?php } ?>

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

           <?php include_once('includes/footer.php');?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
           <?php include_once('includes/footer2.php');?>



    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>
</html>
<?php } ?>