<?php
  session_start();
  require "../config/connection.php";
  $matkul = $_POST["mata_kuliah"];
  $kode = $_POST["kode"];
  $query = "INSERT INTO mata_kuliah(mata_kuliah, kode) VALUES 
            (
              '$matkul', 
              '$kode'
            )";

  mysqli_query($conn, $query);

 $_SESSION["alert"] = "Mata kuliah berhasil ditambahkan!";

  header("Location: ../manage.php");
  exit;