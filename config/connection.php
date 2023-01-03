<?php
  $conn = mysqli_connect("localhost", "root", "", "pnj");
  if(!$conn){
    echo "Connection Failed";
    die();
  }
?>