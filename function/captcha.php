<?php
  session_start();
  header("content-type: image/png");
  $_SESSION["captcha"] = "";

  $gbr = imagecreate(200,50);

  imagecolorallocate($gbr, 167, 218, 239);

  $grey = imagecolorallocate($gbr, 128, 128, 128);
  $black = imagecolorallocate($gbr, 0, 0, 0);

  $font = "../source/monaco.ttf";

  for($i = 0; $i <= 5; $i++){
    $nomor= random_int(0,9);
    $_SESSION["captcha"] .= $nomor;
    $sudut = random_int(-25, 25);
    imagettftext($gbr, 20, $sudut, 50 + 15*$i, 35, $black, $font, $nomor);
    imagettftext($gbr, 20, $sudut, 50 + 15*$i, 36, $grey, $font, $nomor);
  }
  
  imagepng($gbr);
  imagedestroy($gbr);