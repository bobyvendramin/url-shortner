<?php
$servername = "localhost";
$username = "kakoicom_url_shortener"; // replace with your cPanel username or DB user
$password = "lsdzBr#VC=Fh"; // replace with the password you set up in cPanel
$dbname = "kakoicom_url_shortener";       // replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>