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
    <div class="container w-50">
      <h1 class="text-center pb-3">Tambah Dosen</h1>
      <form method="POST" action="function/add_dosen.php">
        <div class="mb-3">
          <label for="nama_dosen" class="form-label">Nama Dosen</label>
          <input type="text" class="form-control" placeholder="Nama Dosen" name="nama_dosen" id="nama_dosen">
        </div>
        <div class="mb-3">
          <label for="nip" class="form-label">NIP</label>
          <input type="text" class="form-control" placeholder="NIP" name="nip" id="nip">
        </div>
        <div class="mb-3">
          <button type="submit" class="btn btn-primary">Tambah Dosen</button>
        </div>
      </form>
      <h1 class="text-center pb-3">Tambah Mata Kuliah</h1>
      <form method="POST" action="function/add_mata_kuliah.php">
        <div class="mb-3">
          <label for="nama_dosen" class="form-label">Mata Kuliah</label>
          <input type="text" class="form-control" placeholder="Mata Kuliah" name="mata_kuliah" id="mata_kuliah">
        </div>
        <div class="mb-3">
          <label for="nip" class="form-label">Kode</label>
          <input type="text" class="form-control" placeholder="Kode" name="kode" id="kode">
        </div>
        <div class="mb-3">
          <button type="submit" class="btn btn-primary">Tamba Mata Kuliahh</button>
        </div>
      </form>
    </div>
  </main> 
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>