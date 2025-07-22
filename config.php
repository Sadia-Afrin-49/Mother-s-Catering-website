<?php
// Start the session on all pages
session_start();

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mothers_catering";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>