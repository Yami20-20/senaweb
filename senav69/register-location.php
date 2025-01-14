<?php
include_once('includes/config.php');
include_once('includes/heart.php');

// Get the POST data
$id = $_POST['id']; // Record ID from the heart rate data
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Get the user ID (assuming the user is logged in and stored in the session)
$u_id = $_SESSION['aid']; 

// Insert the location data into the gps_data table
$query = "INSERT INTO gps_data (u_id, latitude, longitude) VALUES ('$u_id', '$latitude', '$longitude')";
if (mysqli_query($con, $query)) {
    echo "Location registered successfully!";
} else {
    echo "Error: " . mysqli_error($con);
}
?>
