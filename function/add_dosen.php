<?php
  session_start();
  require "../config/connection.php";
  $nama = $_POST["nama_dosen"];
  $nip = $_POST["nip"];
  $query = "INSERT INTO dosen(nama, nip) VALUES 
            (
              '$nama', 
              '$nip'
            )";

  mysqli_query($conn, $query);  

  header("Location: ../manage.php");
  exit;