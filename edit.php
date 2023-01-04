<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit | Jadwal PNJ</title>
  <link rel="shortcut icon" href="https://www.pnj.ac.id/asset/images/logo@2x.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body style="background-color: #f8f8f8; min-height: 100vh">
  <?php
    session_start();
    require "config/connection.php";
    
    $query = "SELECT * FROM dosen";
    $dosenResult = mysqli_query($conn, $query);
    
    $query = "SELECT * FROM mata_kuliah";
    $matkulResult = mysqli_query($conn, $query);

    $id = $_GET["id"];
    $hari = $_GET["hari"];
    $waktu_mulai = $_GET["waktu_mulai"];
    $waktu_selesai = $_GET["waktu_selesai"];
    $kelasArr = explode("-",$_GET["kelas"]);
    
    $kelas_prodi = $kelasArr[0];
    $kelas = $kelasArr[1];

    $matkul = $_GET["mata_kuliah"];
    $dosen = $_GET["nama"];
    $ruang = $_GET["ruang"];
    $tahun = $_GET["tahun_ajaran"];
    $jam = $_GET["jumlah_jam"];
    $semester = $_GET["semester"];
  ?>

  <!-- Navigation bar -->
  <nav class="navbar bg-dark navbar-dark">
    <div class="container-fluid px-4 py-1">
      <a class="navbar-brand" href="index.php"><h2>Politeknik Negeri Jakarta</h2></a>
      <?php if(!isset($_SESSION["login"])): ?>
        <a class="btn btn-primary px-4" href="login.php" role="button">Login</a>
      <?php else: ?>
        <div class="btn-group">
          <button type="button" class="btn btn-outline-secondary text-light border-dark dropdown-toggle fw-semibold" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
            Hi, <?= ucfirst($_SESSION["username"]) ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="function/logout.php" role="button">Logout</a></li>
          </ul>
        </div>
      <?php endif ?>
    </div>
  </nav>
  
  <main class="p-5 d-flex flex-column gap-3">
    <!-- Title -->
    <h1 class="text-center pb-3">Edit Data</h1>
    <div class="container w-50">
      <form method="POST" action="function/edit_jadwal.php">
      <input type="hidden" id="id" name="id" value="<?= $id ?>">
      <div class="mb-3">
          <label for="hari" class="form-label">Hari</label>
          <select name="hari" id="hari" class="form-select">
            <option value="Senin" <?= $hari == "Senin" ? "selected" : "" ?>>Senin</option>
            <option value="Selasa" <?= $hari == "Selasa" ? "selected" : "" ?>>Selasa</option>
            <option value="Rabu" <?= $hari == "Rabu" ? "selected" : "" ?>>Rabu</option>
            <option value="Kamis" <?= $hari == "Kamis" ? "selected" : "" ?>>Kamis</option>
            <option value="Jumat" <?= $hari == "Jumat" ? "selected" : "" ?>>Jumat</option>
          </select>
        </div>

        <label for="kelas" class="form-label">Waktu</label>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="00.00" name="waktu_mulai" id="waktu_mulai" value="<?= $waktu_mulai ?>">
          <span class="input-group-text">-</span>
          <input type="text" class="form-control" placeholder="00.00" name="waktu_selesai" id="waktu_selesai" value="<?= $waktu_selesai ?>">
        </div>

        <label for="kelas" class="form-label">Kelas</label>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="TI" name="kelas_prodi" id="kelas_prodi" value="<?= $kelas_prodi ?>">
          <span class="input-group-text">-</span>
          <input type="text" class="form-control" placeholder="3B" name="kelas" id="kelas" value="<?= $kelas ?>">
        </div>

        <div class="mb-3">
          <label for="matkul" class="form-label">Mata Kuliah</label>
          <select name="matkul" id="matkul" class="form-select">
            <?php while($row = mysqli_fetch_array($matkulResult)) : ?>
              <option value="<?= $row["id"] ?>" <?= $matkul == $row["mata_kuliah"] ? "selected" : "" ?>><?= $row["mata_kuliah"] ?></option>
              <?php endwhile ?>
            </select>
        </div>

        <div class="mb-3">
          <label for="dosen" class="form-label">Dosen</label>
          <select name="dosen" id="dosen" class="form-select">
            <?php while($row = mysqli_fetch_array($dosenResult)) : ?>
              <option value="<?= $row["id"] ?>" <?= $dosen == $row["nama"] ? "selected" : "" ?>><?= $row["nama"] ?></option>
              <?php endwhile ?>
            </select>
        </div>

        <div class="mb-3">
          <label for="ruang" class="form-label">Ruang</label>
          <input type="text" class="form-control" name="ruang" id="ruang" placeholder="Ruang" value="<?= $ruang ?>">
        </div>
        
        <div class="mb-3">  
          <label for="jam" class="form-label">Jumlah Jam</label>
          <input type="number" class="form-control" name="jam" id="jam" placeholder="Jumlah Jam" value="<?= $jam ?>">
        </div>

        <div class="mb-3">
          <label for="tahun" class="form-label">Tahun Ajaran</label>
          <select name="tahun" id="tahun" class="form-select">
            <?php for($i = 2018; $i < 2030; $i++) : ?>
              <option value="<?= "$i - ". $i+1 ?>" <?= $tahun == "$i - ". $i+1 ? "selected" : "" ?>><?= "$i - ". $i+1 ?></option>
              <?php endfor ?>
            </select>
        </div>

        <div class="mb-3">
          <label for="semester" class="form-label">Semester</label>
          <select name="semester" id="semester" class="form-select">
            <option value="Ganjil" <?= "Ganjil" == $semester ? "selected" : "" ?>>Ganjil</option>
            <option value="Genap" <?= "Genap" == $semester ? "selected" : "" ?>>Genap</option>
          </select>
        </div>

        <div class="mb-3">
          <button type="submit" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#tambahModal">
            Edit
          </button>
        </div>
    </form>
    </div>
  </main> 
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>