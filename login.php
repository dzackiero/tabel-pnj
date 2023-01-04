<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Jadwal PNJ</title>
  <link rel="shortcut icon" href="https://www.pnj.ac.id/asset/images/logo@2x.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="source/script.js" defer></script>
</head>
<?php
  session_start();
  require "config/connection.php";
  if(isset($_POST["login"])){
    $username = $_POST["username"];
    $password = $_POST["password"];
    $captcha = $_POST["captcha"];
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    if(mysqli_num_rows($result) > 0 && $captcha == $_SESSION["captcha"]){
      $row = mysqli_fetch_assoc($result);
      if(password_verify($password, $row["password"])){
        $_SESSION["login"] = true;
        $_SESSION["username"] = $username;

        header("Location: index.php");
        exit;
      }
    }

    if($captcha != $_SESSION["captcha"]){
      $error["status"] = true;
      $error["message"] = "Captcha tidak sesuai!";
    } else {
      $error["status"] = true;
      $error["message"] = "Username/Password yang anda masukkan tidak sesuai!";
    }
  }
?>

<body class="vh-100 d-flex justify-content-center align-items-center p-5"
style='background: hsla(212, 35%, 58%, 1);
background: linear-gradient(90deg, hsla(212, 35%, 58%, 1) 0%, hsla(218, 32%, 80%, 1) 100%);
background: -moz-linear-gradient(90deg, hsla(212, 35%, 58%, 1) 0%, hsla(218, 32%, 80%, 1) 100%);
background: -webkit-linear-gradient(90deg, hsla(212, 35%, 58%, 1) 0%, hsla(218, 32%, 80%, 1) 100%);
filter: progid: DXImageTransform.Microsoft.gradient( startColorstr="#6D90B9", endColorstr="#BBC7DC", GradientType=1 );'>
  <main class="rounded bg-white px-4 py-5 shadow-lg" style="min-width: 35rem;">
    <h1 class="text-center mb-4">Login</h1>
    <form method="POST" id="form">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required>
      </div>

      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>

      <div class="mb-3 d-flex justify-content-center captcha-container">
        <img src="function/captcha.php" alt="captcha" class="captcha-image">
      </div>

      <div class="mb-3">
        <label for="captcha" class="form-label">Captcha</label>
        <input type="text" name="captcha" id="captcha" class="form-control" required>
      </div>

      <div class="mb-3">
        <button class="btn btn-primary w-100"  name="login" id="login">Login</button>
      </div>
      <div>
        <a href="index.php" class="btn btn-success w-100">Home</a>
      </div>

    </form>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>