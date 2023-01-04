<?php
  session_start();
  require "../config/connection.php";
  $hari = $_POST["hari"];
  $waktu_mulai = str_replace(":",".",$_POST["waktu_mulai"]);
  $waktu_selesai = str_replace(":",".",$_POST["waktu_selesai"]);
  $kelas = $_POST["kelas_prodi"] . "-" . $_POST["kelas"];
  $matkul = $_POST["matkul"];
  $dosen = $_POST["dosen"];
  $ruang = $_POST["ruang"];
  $tahun = $_POST["tahun"];
  $jam = $_POST["jam"];
  $semester = $_POST["semester"];
  $query = "INSERT INTO jadwal(hari, waktu_mulai, waktu_selesai, kelas, mata_kuliah_id, dosen_id, ruang, jumlah_jam, tahun_ajaran, semester) VALUES 
            (
              '$hari', 
              '$waktu_mulai', 
              '$waktu_selesai', 
              '$kelas', 
              $matkul, 
              $dosen, 
              '$ruang', 
              '$jam', 
              '$tahun', 
              '$semester'
            )";

  mysqli_query($conn, $query);  

  header("Location: ../index.php");
  exit;