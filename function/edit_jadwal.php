<?php
session_start();
  require "../config/connection.php";
  $id = $_POST["id"];
  $hari = $_POST["hari"];
  $waktu_mulai = $_POST["waktu_mulai"];
  $waktu_selesai = $_POST["waktu_selesai"];
  $kelas = $_POST["kelas_prodi"] . "-" . $_POST["kelas"];
  $matkul = $_POST["matkul"];
  $dosen = $_POST["dosen"];
  $ruang = $_POST["ruang"];
  $tahun = $_POST["tahun"];
  $jam = $_POST["jam"];
  $semester = $_POST["semester"];
  $query = "UPDATE jadwal SET
          hari='$hari', 
          waktu_mulai='$waktu_mulai', 
          waktu_selesai='$waktu_selesai', 
          kelas='$kelas', 
          mata_kuliah_id='$matkul', 
          dosen_id='$dosen', 
          ruang='$ruang', 
          jumlah_jam='$jam', 
          tahun_ajaran='$tahun', 
          semester='$semester' 
          WHERE id='$id'"
          ;

  mysqli_query($conn, $query);  

  header("Location: ../index.php");
  exit;