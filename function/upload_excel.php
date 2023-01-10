<?php
  session_start();
  $ext = pathinfo($_FILES["excel"]["name"],PATHINFO_EXTENSION);
  move_uploaded_file($_FILES["excel"]["tmp_name"], "../source/temp_excel.$ext");

  require "../config/connection.php";
  // include the autoloader, so we can use PhpSpreadsheet
  require_once(__DIR__ . '/../vendor/autoload.php');


  # Create a new Xls Reader
  $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

  // Tell the reader to only read the data. Ignore formatting etc.
  $reader->setReadDataOnly(true);

  // Read the spreadsheet file.
  $spreadsheet = $reader->load(__DIR__ . '/../source/temp_excel.xlsx');

  $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
  $data = $sheet->toArray();

  unlink("../source/temp_excel.$ext");

  $header = true;
  // output the data to the console, so you can see what there is.
  foreach ($data as $col) {
      if($header){
        $header = false;
        continue;
      }

      //Dosen
      $dosen = ucfirst($col[5]);

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


      //Mata Kuliah
      $mata_kuliah = ucwords($col[4]);
      $query = "SELECT * FROM mata_kuliah WHERE mata_kuliah = '$mata_kuliah'";
      $result = mysqli_query($conn, $query);

      if(mysqli_num_rows($result) <= 0){
        $query = "INSERT INTO mata_kuliah(mata_kuliah) VALUES ('$mata_kuliah')";
        mysqli_query($conn, $query);  
        $id_mata_kuliah = mysqli_insert_id($conn);

      //Jika Telah Terdaftar
      } else {
        $result = mysqli_fetch_assoc($result);
        $id_mata_kuliah = $result["id"];
      }


      $hari = $col[0];
      
      $waktu = explode(" - ", $col[2]);
      $waktu_mulai = $waktu[0];
      $waktu_selesai = $waktu[1];

      $kelas = explode(" ", $col[3], 2);
      $kelas = $kelas[0] . " - " . $kelas[1];

      $matkul = $id_mata_kuliah;
      $dosen = $id_dosen;
      $ruang = $col[6];
      $jam = $col[7];
      $tahun = $col[8];
      $semester = $col[9];

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
    }

  $_SESSION["alert"] = "Data dari Excel berhasil ditambahkan!";

  header("Location: ../index.php");