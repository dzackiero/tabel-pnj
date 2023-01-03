<?php
  session_start();
  require "config/connection.php";

  $username = $_POST["username"];
  $password = $_POST["password"];
  $captcha = $_POST["captcha"];
  $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
  if(mysqli_num_rows($result) > 0 && $captcha == $_SESSION["captcha"]) {
    $row = mysqli_fetch_assoc($result);
    if(password_verify($password, $row["password"])){
      $_SESSION["login"] = true;
      $_SESSION["username"] = $username;
      echo "";
      exit;
    }
  }

  if($captcha != $_SESSION["captcha"]){
    echo "Captcha tidak sesuai!";
  } else {
    echo "Username/Password yang anda masukkan tidak sesuai!";
  }
?>