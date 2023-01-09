<?php
  session_start();
  require "../config/connection.php";
  var_dump($_GET);
  $id = $_GET["id"];
  $query = "DELETE FROM jadwal WHERE id=$id";

  mysqli_query($conn, $query);  

 $_SESSION["alert"] = "Jadwal berhasil dihapus!";

  header("Location: ../index.php");
  exit;