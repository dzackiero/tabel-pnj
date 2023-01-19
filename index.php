<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home | Jadwal PNJ</title>
  <link rel="shortcut icon" href="https://www.pnj.ac.id/asset/images/logo@2x.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <!-- <script src="source/search.js" defer></script> -->
</head>
<body style="background-color: #f8f8f8; min-height: 100vh">
  <?php
    session_start();

    require "config/connection.php";
    
    $query = "SELECT * FROM dosen";
    $dosen = mysqli_query($conn, $query);
    
    $query = "SELECT * FROM mata_kuliah";
    $matkul = mysqli_query($conn, $query);

    //Search and sort
    $hari = isset($_GET["hari"]) ? $_GET["hari"] : "";
    $kelas = isset($_GET["kelas"]) ? $_GET["kelas"] : "";  
    $ruang = isset($_GET["ruang"]) ? $_GET["ruang"] : "";  
    $mata_kuliah = isset($_GET["mata_kuliah"]) ? $_GET["mata_kuliah"] : "";  
    $nama_dosen = isset($_GET["nama_dosen"]) ? $_GET["nama_dosen"] : "";  
    // $keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";  
    $sort = isset($_GET["sort"]) ? $_GET["sort"] : "";
    $sortBy = isset($_GET["sortby"]) ? $_GET["sortby"] : "asc";

    $query = "SELECT JA.id, hari, waktu_mulai, waktu_selesai, kelas, ruang, jumlah_jam, tahun_ajaran, semester, mata_kuliah, nama FROM JADWAL AS JA JOIN MATA_KULIAH AS MK ON MK.ID = JA.MATA_KULIAH_ID JOIN DOSEN AS DS ON DS.ID = JA.DOSEN_ID";
    
    if($hari != "" || $kelas != ""  || $ruang != ""  || $mata_kuliah != ""  || $nama_dosen != ""){
      $query .= " WHERE ";

      if($hari != ""){
        $queryLike[] = "hari LIKE '%$hari%'";
      }
      if($kelas != ""){
        $queryLike[] = "kelas LIKE '%$kelas%'";
      }
      if($ruang != ""){
        $queryLike[] = "ruang LIKE '%$ruang%'";
      }
      if($mata_kuliah != ""){
        $queryLike[] = "mata_kuliah LIKE '%$mata_kuliah%'";
      }
      if($nama_dosen != ""){
        $queryLike[] = "nama LIKE '%$nama_dosen%'";
      }
      $query .= implode(" OR ", $queryLike);
      
    }

    //jumlah_jam LIKE '%$keyword%' OR
    // tahun_ajaran LIKE '%$keyword%' OR
    // semester LIKE '%$keyword%' OR

    $jadwal = mysqli_query($conn, $query);
    if(mysqli_num_rows($jadwal) <= 0){
      // $query = "SELECT JA.id, hari, waktu_mulai, waktu_selesai, kelas, ruang, jumlah_jam, tahun_ajaran, semester, mata_kuliah, nama FROM JADWAL AS JA JOIN MATA_KULIAH AS MK ON MK.ID = JA.MATA_KULIAH_ID JOIN DOSEN AS DS ON DS.ID = JA.DOSEN_ID";
      $notFound = true;
    };


    if($sort != ""){
      if($sort == "hari"){
        $query .= " ORDER BY field(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')";
      } else {
        $query .= " ORDER BY $sort $sortBy";
      }
    }
    $jadwal = mysqli_query($conn, $query);

    //Paginasi
    $per_page = 10;
    $data_count = mysqli_num_rows($jadwal);
    $total_page = ceil($data_count / $per_page);
    $current_page = isset($_GET["halaman"]) ? $_GET["halaman"] : 1;
    $first_data = ($per_page * $current_page) - $per_page;

    $query .= " LIMIT $first_data, $per_page";

    $jadwal = mysqli_query($conn, $query);
?>

  <!-- Navigation bar -->
  <nav class="navbar bg-dark navbar-dark">
    <div class="container-fluid px-4 py-1">
      <a class="navbar-brand inline" href="index.php">
        <h2>Politeknik Negeri Jakarta</h2>
      </a>
      <?php if(!isset($_SESSION["login"])): ?>
        <a class="btn btn-primary px-4" href="login.php" role="button">Login</a>
      <?php else: ?>
        <div class="btn-group">
          <button type="button" class="btn btn-outline-secondary text-light border-dark dropdown-toggle fw-semibold" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
            Hi, <?= ucfirst($_SESSION["username"]) ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="manage.php" role="button">Manajemen Data</a></li>
            <li><a class="dropdown-item" href="function/logout.php" role="button">Logout</a></li>
          </ul>
        </div>
      <?php endif ?>
    </div>
  </nav>

  <!-- Toast -->
  <?php if(isset($_SESSION["alert"])): ?>
    <div class="alert alert-primary alert-dismissible fade show" role="alert" 
    style="
    position: absolute;
    top:6rem;
    right:1rem;
    "
    >
      <?= $_SESSION["alert"] ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php unset($_SESSION["alert"]) ?>
  <?php endif ?>

  <main class="p-5 d-flex flex-column gap-3">
    <!-- Title -->
    <div class="d-flex justify-content-center">
      <img src="https://file.maukuliah.id/img/logo/Poltek-Negeri-Jakarta.png" alt="" width="250">
    </div>
    <h1 class="text-center pb-3">Tabel Jadwal Siswa</h1>

    <!-- Excel -->
    <?php if(isset($_SESSION["login"])) : ?>
      <div class="container d-flex gap-5 justify-content-center">
        <form action="function/upload_excel.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="excel" class="form-label">Excel</label>
            <input type="file" class="form-control" accept=".xls,.xlsx" id="excel" name="excel" style="width: min-content;" required>
          </div>
          <button type="submit" class="btn btn-primary">Upload Excel</button>
        </form>
        <form action="function/upload_json.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="json" class="form-label">JSON</label>
            <input type="file" class="form-control" accept=".json" id="json" name="json" style="width: min-content;" required>
          </div>
          <button type="submit" class="btn btn-primary">Upload JSON</button>
        </form>
      </div>
    <?php endif ?>
    
    <!-- Search And Add -->
    <div class="container">
      <div class="row mt-1">
        
        <!-- Search -->
        <div class="mb-3" style="min-width: 15rem;">
          <form method="GET" class="container gap-2">
            <div class="row">
              <div class="col">
                <label for="hari" class="form-label">Hari</label>
                <input type="text" class="form-control" name="hari" id="hari" placeholder="Pencarian" autocomplete="off" value="<?= $hari ?>">
              </div>

              <div class="col">
                <label for="kelas" class="form-label">Kelas</label>
                <input type="text" class="form-control" name="kelas" id="kelas" placeholder="Pencarian" autocomplete="off" value="<?= $kelas ?>">
              </div>
            </div>

            <div class="row">
              <div class="col">
                <label for="ruang" class="form-label">Ruang</label>
                <input type="text" class="form-control" name="ruang" id="ruang" placeholder="Pencarian" autocomplete="off" value="<?= $ruang ?>">
              </div>

              <div class="col">
                <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
                <input type="text" class="form-control" name="mata_kuliah" id="mata_kuliah" placeholder="Pencarian" autocomplete="off" value="<?= $mata_kuliah ?>">
              </div>
              
              <div class="col">
                <label for="nama_dosen" class="form-label">Nama Dosen</label>
                <input type="text" class="form-control" name="nama_dosen" id="nama_dosen" placeholder="Pencarian" autocomplete="off" value="<?= $nama_dosen ?>">
              </div>
            </div>



        </div>


        <!-- Sort Option -->
        <div class="col">
          <select class="form-select" id="sort-select" name="sort">
            <option value="id" <?= $sort == "id" ? "selected" : "" ?>>Nomor</option>
            <option value="hari" <?= $sort == "hari" ? "selected" : "" ?>>Hari</option>
            <option value="mata_kuliah" <?= $sort == "mata_kuliah" ? "selected" : "" ?>>Mata Kuliah</option>
            <option value="nama" <?= $sort == "nama" ? "selected" : "" ?>>Dosen</option>
            <option value="ruang" <?= $sort == "ruang" ? "selected" : "" ?>>Ruang</option>
            <option value="jumlah_jam" <?= $sort == "jumlah_jam" ? "selected" : "" ?>>Jumlah Jam</option>
            <option value="tahun_ajaran" <?= $sort == "tahun_ajaran" ? "selected" : "" ?>>Tahun Ajaran</option>
            <option value="semester" <?= $sort == "semester" ? "selected" : "" ?>>Semester</option>
          </select>
        </div>
        <!-- Ascending/Decending -->
        <div class="col">
          <select class="form-select" id="sortby" name="sortby">
            <option value="asc" <?= $sortBy == "asc" ? "selected" : "" ?>>Ascending</option>
            <option value="desc" <?= $sortBy == "desc" ? "selected" : "" ?>>Descending</option>
          </select>
        </div>
        
        <!-- Search And Clear Button -->
        <div class="col d-flex align-items-center flex">
          <button type="submit" class="btn btn-primary px-4" style="margin-right: 0.5rem;">Cari</button>
          <a class="btn btn-primary px-4" href="index.php">Clear</a>
          </form>
        </div>

        <!-- Add Jadwal -->
        <div class="col d-flex align-items-center justify-content-end">
          <?php if(isset($_SESSION["login"])): ?>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
            Tambah
          </button>
          <?php endif ?>
        </div>
        
      </div>
    </div>

    <div class="container overflow-auto">
      <!-- Table -->
      <div class="overflow-y" id="table-container">
        <table class="table table-dark table-fixed table-striped table-hover border text-center">
          <thead>
              <tr class="text-nowrap">
                <th scope="col">No</th>
                <th scope="col">Hari</th>
                <th scope="col">Slot Waktu</th>
                <th scope="col">Kelas</th>
                <th scope="col">Mata Kuliah</th>
                <th scope="col">Dosen</th>
                <th scope="col">Ruang</th>
                <th scope="col">Jumlah Jam</th>
                <th scope="col">Tahun Ajaran</th>
                <th scope="col">Semester</th>
                <?php if(isset($_SESSION["login"])): ?>
                  <th scope="col">Aksi</th>
                <?php endif ?>
              </tr>
          </thead>
          <tbody>
            <?php $i = $first_data+1; ?>
            <?php while($row = mysqli_fetch_array($jadwal)) :?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= $row['hari'] ?></td>
              <td><?= $row['waktu_mulai'] . " - " . $row['waktu_selesai'] ?></td>
              <td><?= $row['kelas'] ?></td>
              <td><?= $row['mata_kuliah'] ?></td>
              <td><?= $row['nama'] ?></td>
              <td><?= $row['ruang'] ?></td>
              <td><?= $row['jumlah_jam'] ?></td>
              <td><?= $row['tahun_ajaran'] ?></td>
              <td><?= $row['semester'] ?></td>
              <?php
                $urlQuery = "id=".$row['id'].
                "&hari=".$row['hari'].
                "&waktu_mulai=".$row['waktu_mulai'].
                "&waktu_selesai=".$row['waktu_selesai'].
                "&kelas=".$row['kelas'].
                "&mata_kuliah=".$row['mata_kuliah'].
                "&nama=".$row['nama'].
                "&ruang=".$row['ruang'].
                "&jumlah_jam=".$row['jumlah_jam'].
                "&tahun_ajaran=".$row['tahun_ajaran'].
                "&semester=".$row['semester']
                ;
              ?>
              <?php if(isset($_SESSION["login"])): ?>
              <td><a href="function/delete_jadwal.php?id=<?= $row[0] ?>" class="text-danger">Delete</a> | <a href="edit.php?<?= $urlQuery ?>" class="text-info">Edit</a></td>
              <?php endif ?>
            </tr>
            <?php endwhile ?>
            <?php if(isset($notFound)): ?>
              <tr>
                <td colspan="11" class="table-light">
                  <h3>Data tidak ditemukan!</h3>
                </td>
              </tr>
            <?php endif ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="container">
      <nav aria-label="Page navigation">
        <ul class="pagination">
          <!-- Previous -->
          <?php
              $paginateQuery = "";
              $paginateQuery .= $hari != "" ? "&hari=$hari" : "";
              $paginateQuery .= $kelas != "" ? "&kelas=$kelas" : "";
              $paginateQuery .= $ruang != "" ? "&ruang=$ruang" : "";
              $paginateQuery .= $mata_kuliah != "" ? "&mata_kuliah=$mata_kuliah" : "";
              $paginateQuery .= $nama_dosen != "" ? "&nama_dosen=$nama_dosen" : "";
            ?>

          <li class="page-item">
            <a class="page-link <?= $current_page-1 < 1 ? "disabled" : "" ?>"
            href="index.php?halaman=<?= $current_page-1 ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
              Previous
            </a>
          </li>

          <!-- If Less Than 6-->
          <?php if($total_page <= 5) :?>

            <?php for($i = 1; $i <= $total_page; $i++) : ?>
              <li class="page-item">
                <a class="page-link <?= $current_page == $i ? "active" : "" ?>"
                href="index.php?halaman=<?= $i ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= $i ?>
                </a>
              </li>
            <?php endfor ?>
          <?php endif ?>

          <?php if($total_page > 5) : ?>

            <!-- Current Page First 3 Item -->
            <?php if($current_page <= 3): ?>
              <?php for($i = 1; $i <= 4; $i++) : ?>
              <li class="page-item">
                <a class="page-link <?= $current_page == $i ? "active" : "" ?>"
                href="index.php?halaman=<?= $i ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= $i ?>
                </a>
              </li>
              <?php endfor ?>
              <li class="page-item">
                <a class="page-link"
                href="index.php?halaman=<?= 5 ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= "..." ?>
                </a>
              </li>
              <li class="page-item">
                <a class="page-link <?= $current_page == $total_page ? "active" : "" ?>"
                href="index.php?halaman=<?= $total_page ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= $total_page ?>
                </a>
              </li>
            <?php endif ?>
              
            <!-- Current Page In Between First 3 Item and Last 3 item -->
            <?php if($current_page > 3 && $current_page < $total_page - 2): ?>
              <li class="page-item">
                <a class="page-link"
                href="index.php?halaman=<?= 1 ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= 1 ?>
                </a>
              </li>
              <li class="page-item">
                <a class="page-link"
                href="index.php?halaman=<?= $current_page - 2 ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= "..." ?>
                </a>
              </li>
              <?php for($i = $current_page-1; $i <= $current_page+1; $i++) : ?>
              <li class="page-item">
                <a class="page-link <?= $current_page == $i ? "active" : "" ?>"
                href="index.php?halaman=<?= $i ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= $i ?>
                </a>
              </li>
              <?php endfor ?>
              <li class="page-item">
                <a class="page-link"
                href="index.php?halaman=<?= $current_page + 2 ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= "..." ?>
                </a>
              </li>
              <li class="page-item">
                <a class="page-link <?= $current_page == $total_page ? "active" : "" ?>"
                href="index.php?halaman=<?= $total_page ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= $total_page ?>
                </a>
              </li>
            <?php endif ?>

            <!-- Current Page In Last 3 Item -->
            <?php if($current_page >= $total_page - 2): ?>
              <li class="page-item">
                <a class="page-link"
                href="index.php?halaman=<?= 1 ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= 1 ?>
                </a>
              </li>
              <li class="page-item">
                <a class="page-link"
                href="index.php?halaman=<?= $total_page-4 ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= "..." ?>
                </a>
              </li>
              <?php for($i = $total_page-3; $i <= $total_page-1; $i++) : ?>
              <li class="page-item">
                <a class="page-link <?= $current_page == $i ? "active" : "" ?>"
                href="index.php?halaman=<?= $i ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= $i ?>
                </a>
              </li>
              <?php endfor ?>
              <li class="page-item">
                <a class="page-link <?= $current_page == $total_page ? "active" : "" ?>"
                href="index.php?halaman=<?= $total_page ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
                  <?= $total_page ?>
                </a>
              </li>
            <?php endif ?>

          <?php endif ?>
          <!-- Next -->
          <li class="page-item <?= $current_page+1 > $total_page ? "disabled" : "" ?>">
            <a class="page-link" href="index.php?halaman=<?= $current_page+1 ?><?= $paginateQuery ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">
              Next
            </a>
          </li>

        </ul>
      </nav>
    </div>
  </main> 
  
  <!-- Modal -->
  <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Jadwal</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="function/add_jadwal.php">
            
            <div class="mb-3">
              <label for="hari" class="form-label">Hari</label>
              <select name="hari" id="hari" class="form-select" >
                <option value="Senin">Senin</option>
                <option value="Selasa">Selasa</option>
                <option value="Rabu">Rabu</option>
                <option value="Kamis">Kamis</option>
                <option value="Jumat">Jumat</option>
              </select>
            </div>

            <label for="kelas" class="form-label">Waktu</label>
            <div class="input-group mb-3">
              <input type="time" class="form-control" placeholder="00.00" name="waktu_mulai" id="waktu_mulai" required>
              <span class="input-group-text">-</span>
              <input type="time" class="form-control" placeholder="00.00" name="waktu_selesai" id="waktu_selesai" required>
            </div>

            <label for="kelas" class="form-label">Kelas</label>
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="TI" name="kelas_prodi" id="kelas_prodi" required>
              <span class="input-group-text">-</span>
              <input type="text" class="form-control" placeholder="3B" name="kelas" id="kelas" required>
            </div>

            <div class="mb-3">
              <label for="matkul" class="form-label">Mata Kuliah</label>
              <select name="matkul" id="matkul" class="form-select">
                <?php while($row = mysqli_fetch_array($matkul)) : ?>
                  <option value="<?= $row["id"] ?>"><?= $row["mata_kuliah"] ?></option>
                  <?php endwhile ?>
                </select>
            </div>

            <div class="mb-3">
              <label for="dosen" class="form-label">Dosen</label>
              <select name="dosen" id="dosen" class="form-select">
                <?php while($row = mysqli_fetch_array($dosen)) : ?>
                  <option value="<?= $row["id"] ?>"><?= $row["nama"] ?></option>
                  <?php endwhile ?>
                </select>
            </div>

            <div class="mb-3">
              <label for="ruang" class="form-label">Ruang</label>
              <input type="text" class="form-control" name="ruang" id="ruang" placeholder="Ruang" required>
            </div>
            
            <div class="mb-3">
              <label for="jam" class="form-label">Jumlah Jam</label>
              <input type="number" class="form-control" min="1" name="jam" id="jam" placeholder="Jumlah Jam" required>
            </div>

            <div class="mb-3">
              <label for="tahun" class="form-label">Tahun Ajaran</label>
              <select name="tahun" id="tahun" class="form-select">
                <?php for($i = 2018; $i < 2030; $i++) : ?>
                  <option value="<?= "$i - ". $i+1 ?>"><?= "$i - ". $i+1 ?></option>
                  <?php endfor ?>
                </select>
            </div>

            <div class="mb-3">
              <label for="semester" class="form-label">Semester</label>
              <select name="semester" id="semester" class="form-select">
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
              </select>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Tambah</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>