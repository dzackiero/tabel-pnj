<?php
  session_start();

  require "../config/connection.php";
  
  $ext = pathinfo($_FILES["json"]["name"],PATHINFO_EXTENSION);
  move_uploaded_file($_FILES["json"]["tmp_name"], "../source/temp_json.$ext");

  $json = file_get_contents("../source/temp_json.$ext");

  $full_data = json_decode($json, true);

  unlink("../source/temp_json.$ext");

  foreach ($full_data as $data) {
    $hari = $data["hari"];
    $waktu_mulai = $data["waktu_mulai"];
    $waktu_selesai = $data["waktu_selesai"];
    $kelas = $data["kelas"];
    $dosen = ucfirst($data["dosen"]);

    $query = "SELECT * FROM dosen WHERE nama = '$dosen'";
    $result = mysqli_query($conn, $query);
    
    //Jika Dosen Belum Terdaftar
    if(mysqli_num_rows($result) <= 0){
      $nip = rand(pow(10, 18-1), pow(10, 18)-1); 
      
      $query = "INSERT INTO dosen(nama, nip) VALUES ('$dosen', '$nip')";
      
      mysqli_query($conn, $query);  
      $id_dosen = mysqli_insert_id($conn);

    //Jika Telah Terdaftar
    } else {
      $result = mysqli_fetch_assoc($result);
      $id_dosen = $result["id"];
    }


    $matkul = ucwords($data["matkul"]);
    $query = "SELECT * FROM mata_kuliah WHERE mata_kuliah = '$matkul'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) <= 0){
      $query = "INSERT INTO mata_kuliah(mata_kuliah) VALUES ('$matkul')";
      mysqli_query($conn, $query);  
      $id_matkul = mysqli_insert_id($conn);

    //Jika Telah Terdaftar
    } else {
      $result = mysqli_fetch_assoc($result);
      $id_matkul = $result["id"];
    }

    $ruang = $data["ruang"];
    $jam = $data["jam"];
    $tahun = $data["tahun"];
    $semester = $data["semester"];

    $query = "INSERT INTO jadwal(hari, waktu_mulai, waktu_selesai, kelas, mata_kuliah_id, dosen_id, ruang, jumlah_jam, tahun_ajaran, semester) VALUES 
    (
      '$hari', 
      '$waktu_mulai', 
      '$waktu_selesai', 
      '$kelas', 
      $id_matkul, 
      $id_dosen, 
      '$ruang', 
      '$jam', 
      '$tahun', 
      '$semester'
    )";

    mysqli_query($conn, $query);
  }

  $_SESSION["alert"] = "Data dari JSON berhasil ditambahkan!";

  header("Location: ../index.php");
?>