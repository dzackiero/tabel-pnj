
<?php
  session_start();
  require "./config/connection.php";

  $keyword = $_GET["keyword"];  
  $sort = $_GET["sort"];

  if ($sort == "hari"){
    $sort = "field(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')";
  }
  
  $query = "SELECT JA.id, hari, waktu_mulai, waktu_selesai, kelas, ruang, jumlah_jam, tahun_ajaran, semester, mata_kuliah, nama FROM JADWAL AS JA JOIN MATA_KULIAH AS MK ON MK.ID = JA.MATA_KULIAH_ID JOIN DOSEN AS DS ON DS.ID = JA.DOSEN_ID WHERE
  hari LIKE '%$keyword%' OR
  -- waktu_mulai LIKE '%$keyword%' OR
  -- waktu_selesai LIKE '%$keyword%' OR
  kelas LIKE '%$keyword%' OR
  ruang LIKE '%$keyword%' OR
  jumlah_jam LIKE '%$keyword%' OR
  tahun_ajaran LIKE '%$keyword%' OR
  semester LIKE '%$keyword%' OR
  mata_kuliah LIKE '%$keyword%' OR
  nama LIKE '%$keyword%'
  ORDER BY $sort";

  $jadwal = mysqli_query($conn, $query);
?>
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
    <?php $i = 1; ?>
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
        $urlQuery = "id=".$row[0].
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
  </tbody>
</table>