<?php
  session_start();
  require "../config/connection.php";
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $query = "INSERT INTO user(username, password) VALUES 
            (
              '$username', 
              '$password'
            )";

  mysqli_query($conn, $query);  
  
  $_SESSION["alert"] = "User berhasil ditambahkan!";
  
  header("Location: ../manage.php");
  exit;