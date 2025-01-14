<?php 
session_start();
// DB connection
include_once('includes/config.php');
include_once('includes/heart.php');
error_reporting(0);

// Validating Session
if (strlen($_SESSION['aid'] == 0)) {
    header('location:logout.php');
} else {

// Code for record deletion
if ($_GET['action'] == 'delete') {    
    $bpid = intval($_GET['bpid']);    
    $query = mysqli_query($con, "DELETE FROM tblbpdetails WHERE id='$bpid'");
    echo '<script>alert("Record deleted")</script>';
    echo "<script>window.location.href='manage-bp-details.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BP Monitoring Management System | Manage BP Details</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            display: none;
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        #map {
            margin-top: 10px;
            height: 400px;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include_once('includes/sidebar.php'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include_once('includes/topbar.php'); ?>

                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Manage Family Members BP Details</h1>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Family Members BP Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>BPM</th>
                                            <th>AVG BPM</th>
                                            <th>Type</th>
                                            <th>BP DATE TIME</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php 
$uid = intval($_SESSION['aid']);
$query = mysqli_query($con1, "SELECT id, bpm, avg_bpm, isAbnormalHeartRate, timestamp FROM heart_rate_data WHERE u_id = 1");
while ($row = mysqli_fetch_array($query)) {
?>
<tr>
    <td><?php echo $row['bpm']; ?></td>
    <td><?php echo $row['avg_bpm']; ?></td>
    <td><?php echo $row['isAbnormalHeartRate'] ? 'Abnormal' : 'Normal'; ?></td>
    <td><?php echo $row['timestamp']; ?></td>
    <td>
        <a href="#" onclick="openMapModal(<?php echo $row['id']; ?>);">
            <i class="fa fa-map-marker" aria-hidden="true" style="color:blue" title="Manage Location"></i>
        </a>
    </td>
</tr>
<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

<!-- Modal for Map -->
<div id="mapModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeMapModal()">&times;</span>
        <h2>Manage Location</h2>
        <div id="map"></div>
        <form id="locationForm">
            <input type="hidden" id="recordId" name="recordId">
            <label for="latitude">Latitude:</label>
            <input type="text" id="latitude" name="latitude" readonly>
            <label for="longitude">Longitude:</label>
            <input type="text" id="longitude" name="longitude" readonly>
            <button type="button" onclick="registerLocation()">Register Location</button>
        </form>
    </div>
</div>

<script>
    function openMapModal(recordId) {
        document.getElementById('mapModal').style.display = 'block';
        document.getElementById('recordId').value = recordId;

        // Initialize the map
        var map = L.map('map').setView([12.8797, 121.7740], 6); // Default location in case geolocation fails
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Try to get the user's current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                // Set the map view to the user's current location
                map.setView([lat, lng], 13); // Zoom level 13 for better visibility

                // Add a marker at the user's location
                var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

                // Update latitude and longitude values when the marker is dragged
                marker.on('dragend', function () {
                    var latlng = marker.getLatLng();
                    document.getElementById('latitude').value = latlng.lat.toFixed(6);
                    document.getElementById('longitude').value = latlng.lng.toFixed(6);
                });

                // Update the form with the initial location
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
            }, function(error) {
                alert("Error getting location: " + error.message);
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function closeMapModal() {
        document.getElementById('mapModal').style.display = 'none';
    }

    function registerLocation() {
        var recordId = document.getElementById('recordId').value;
        var latitude = document.getElementById('latitude').value;
        var longitude = document.getElementById('longitude').value;

        // Send the data to the register-location.php script
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'register-location.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText); // Show success message
                closeMapModal(); // Close the modal after success
            }
        };
        xhr.send(`id=${recordId}&latitude=${latitude}&longitude=${longitude}`);
    }
</script>

</body>
</html>
<?php } ?>
