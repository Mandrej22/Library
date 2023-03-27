<?php
$con = mysqli_connect("localhost","root","","library_db");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
} else
    echo "Succesful Connection!";

// Perform query
$result = mysqli_query($con, "SELECT * FROM users");
if ($result) {
    while($row = mysqli_fetch_assoc($result)) {
        var_dump($row);
      }
  }    
?>