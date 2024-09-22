<?php
// Memulai session
session_start();

// Memeriksa apakah user sudah login atau belum dengan mengecek nilai session 'level'
if ($_SESSION['level'] == "") {
    // Jika belum login, mengalihkan ke halaman login dengan pesan 'gagal'
    header("location:../index.php?pesan=gagal");
}

// Meng-include file konfigurasi untuk koneksi database
include '../config.php';

// Mengambil nilai 'search' dari query string, jika ada. Jika tidak ada, default menjadi string kosong
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Menyiapkan query untuk menyeleksi data novel dari database
$sql = "SELECT novel.id_novel, novel.poster, novel.judul_novel, novel.sinopsis, novel.tahun_rilis, novel.rating, novel.jumlah_user, author.nama_author 
        FROM novel
        LEFT JOIN novel_genre ON novel.id_novel = novel_genre.id_novel
        LEFT JOIN genre ON novel_genre.id_genre = genre.id_genre
        LEFT JOIN author ON novel.id_author = author.id_author 
        WHERE novel.judul_novel LIKE ? 
        GROUP BY novel.judul_novel";

// Menggunakan prepared statement untuk mencegah SQL injection
$stmt = $conn->prepare($sql);
$search_param = "%$search%"; // Menambahkan wildcard untuk pencarian LIKE
$stmt->bind_param("s", $search_param); // Mengikat parameter ke query
$stmt->execute(); // Menjalankan query
$result = $stmt->get_result(); // Mendapatkan hasil query
$result->fetch_assoc(); // Mengambil hasil query dalam bentuk asosiatif
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
  body {
    padding: 0;
    margin: 0;
  }

  div.container-fluid {
    padding: 0;
    margin: 0;
  }

  /* sidebar */
  div.sidebar {
    height: 100vh;
  }

  main {
    height: 1000px;
  }


  /* main content */
  div.main {
    background-color: orange;
    height: 100vh;
    overflow: auto;
  }

  #search {
    flex-basis: 50%;
  }
  </style>
</head>

<body cz-shortcut-listen="true" class="bg-dark">
  <!-- navbar -->
  <nav class="navbar shadow-lg navbar-expand-md px-4 navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">WahyuNovel</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse  " id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="daftar_novel.php">Daftar Novel</a>
          </li>
        </ul>
        <form method="GET" class="d-flex   gap-2" role="search">
          <input class="form-control" name="search" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-warning" type="submit">Search</button>
          <a href="../logout.php" class="btn btn-outline-danger w-50">Log-out</a>
        </form>
      </div>
    </div>
  </nav>

  <main class="container pt-3 bg-dark ">
    <div class="bg-dark p-3 mt-5 rounded d-flex gap-4 flex-wrap text-white justify-content-center">
      <?php foreach($result as $row): ?>
      <!-- card -->
      <div class="card mb-3 bg-warning shadow-lg" style="max-width:47%;">
        <div class="row g-0">
          <div class="col-md-4">
            <img class="img-fluid rounded-start"
              src="../images/<?= htmlspecialchars(str_replace('=','"', str_replace("_","'", $row['poster']))); ?>">
          </div>
          <div class="col-md-8">
            <div class="card-body p-3">
              <h5 class="card-title p-0 mb-2 text-truncate">
                <b><?= htmlspecialchars($row['judul_novel'], ENT_QUOTES, 'UTF-8'); ?></b>
              </h5>

              <div class="info d-flex flex-wrap mb-2 gap-0">
                <p class="p-0 flex-fill m-0">Rilis: <?= htmlspecialchars($row['tahun_rilis'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="p-0 flex-fill m-0">Author: <?= htmlspecialchars($row['nama_author'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="p-0 w-100 m-0">Rating: <?= htmlspecialchars($row['rating'], ENT_QUOTES, 'UTF-8'); ?></p>
              </div>

              <p class="card-text lh-1 text-wrap text-truncate" style="display: block; height: 68px;">
                <small>
                  <?=  str_replace('=','"', str_replace("_","'",  htmlspecialchars($row['sinopsis']))); ?>
                </small>
              </p>
              <p class="p-0 m-0 align-self-end d-flex gap-2 justify-content-end">
                <a href="./read_novel.php?id=<?php echo htmlspecialchars($row['id_novel']); ?>" class="btn btn-dark btn-outline-warning"><b>Baca</b></a>
              </p>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
  </main>



  <!-- Menghubungkan Bootstrap JS, Popper.js, dan jQuery -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</body>

</html>