<?php
// Memulai session
session_start();

// Memeriksa apakah user yang mengakses halaman ini sudah login sebagai admin
if ($_SESSION['level'] !== "admin") {
    // Jika bukan admin, menghancurkan session dan mengalihkan ke halaman login dengan pesan 'gagal'
    session_destroy();
    header("location:../index.php?pesan=gagal");
    exit; // Menghentikan eksekusi script
}

// Meng-include file konfigurasi untuk koneksi database
include '../config.php';

// Mengambil nilai 'search' dari query string, jika ada. 
// Jika tidak ada, default menjadi string kosong
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mengambil data novel beserta genre dan author dari database berdasarkan pencarian
$sql = "SELECT novel.id_novel, novel.poster, novel.judul_novel, novel.sinopsis, novel.tahun_rilis, 
        novel.jumlah_user, author.nama_author, 
        GROUP_CONCAT(genre.nama_genre SEPARATOR ', ') as genres
        FROM novel
        LEFT JOIN novel_genre ON novel.id_novel = novel_genre.id_novel
        LEFT JOIN genre ON novel_genre.id_genre = genre.id_genre
        LEFT JOIN author ON novel.id_author = author.id_author
        WHERE novel.judul_novel LIKE ?
        GROUP BY novel.id_novel";

// Menggunakan prepared statement untuk mencegah SQL injection
$stmt = $conn->prepare($sql);
$search_param = "%$search%"; // Menambahkan wildcard untuk pencarian LIKE
$stmt->bind_param("s", $search_param); // Mengikat parameter ke query
$stmt->execute(); // Menjalankan query
$result = $stmt->get_result(); // Mendapatkan hasil query
?>




<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WahyuNovel | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
    crossorigin="anonymous">
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



    /* main content */
    div.main {
      background-color: orange;
      height: 100vh;
      overflow: auto;
      white-space: nowrap;
    }

    #search {
      flex-basis: 50%;
    }
    </style>
  </head>

  <body>

    <div class="container-fluid d-flex">
      <!-- sidebar -->
      <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px;">
        <a href="./index.php"
          class="d-flex text-reset align-items-center gap-4 mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-book"
            viewBox="0 0 16 16">
            <path
              d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-
              .493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 
              3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 
              5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-
              .881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 
              2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 
              8.985.936 8 1.783" />
          </svg>
          <span class="fs-4">WahyuNovel</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
          <li class="nav-item ">
            <a href="./index.php" class="nav-link text-reset active d-flex align-items-center gap-3" aria-current="page">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                class="bi bi-house my-auto" viewBox="0 0 16 16">
                <path
                  d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 
                  15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.
                  5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
              </svg>
              Dashboard
            </a>
          </li>
          <li>
            <a href="./add_novel.php" class="nav-link text-reset text-white d-flex align-items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-journal-plus" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                  d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1
                  .5V6a.5.5 0 0 1 .5-.5" />
                <path
                  d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 
                  1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                <path
                  d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 
                  0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 
                  0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
              </svg>
              Tambah Novel
            </a>
          </li>
          <li>
            <a href="./manage_genre.php" class="nav-link text-reset text-white d-flex align-items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-brilliance"
                viewBox="0 0 16 16">
                <path
                  d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16M1 8a7 7 0 0 0 7 7 3.5 3.5 0 1 0 0-7 3.5 3.5 0 1 1 0-7 7 7 0 0 0-7 7" />
              </svg>
              Manage Genre
            </a>
          </li>
          <li>
            <a href="manage_author.php" class="nav-link text-reset text-white d-flex align-items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen"
                viewBox="0 0 16 16">
                <path
                  d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 
                  1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 
                  4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 
                  0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z" />
              </svg>
              Manage Author
            </a>
          </li>
          <li>
            <a href="manage_user.php" class="nav-link text-reset text-white d-flex align-items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people"
                viewBox="0 0 16 16">
                <path
                  d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 
                  9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 
                  0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 
                  0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 
                  10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3
                   3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
              </svg>
              Manage User
            </a>
          </li>
          <li>
            <a href="#" class="nav-link text-reset text-white d-flex align-items-center gap-3"
              onclick="opennewtab('print_pdf.php');">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                  d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 
                  1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 
                  .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-
                  .179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 
                  .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 
                  0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 
                  .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-
                  .483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z" />
              </svg>
              Print PDF
            </a>
          </li>
        </ul>
        <hr>
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" class="rounded-circle me-2" width="32" height="32">
            <strong><?= $_SESSION['username']?></strong>
          </a>
          <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li><a class="dropdown-item" href="../logout.php">Log out</a></li>
          </ul>
        </div>
      </div>

      <!-- main content -->
      <div class="main d-flex flex-grow-1 p-3 flex-column  ">
        <h2 class="text-center  align-self-strech"><b> Daftar Novel</b></h2>


        <form method="GET" action="index.php" class="d-flex justify-content-center gap-2 mb-2">
          <input type="text" class="form-control flex-shrink-0 flex-grow-0" id="search" name="search"
            value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari Novel...">
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
        
        <table class="table table-bordered table-striped table-hover">
          <thead class="table-dark text-center">
            <tr>
              <th>ID</th>
              <th>Gambar</th>
              <th>Judul Novel</th>
              <th>Tahun Rilis</th>
              <th>Author</th>
              <th>Genre</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <!-- Looping melalui hasil query dan menampilkan setiap baris data -->
            <?php while($row = $result->fetch_assoc()): ?>
              
            <tr>
              <td><?=  htmlspecialchars($row['id_novel']); ?></td>
              <td><?=  "<img width='80px' src='../images/". 
                htmlspecialchars(str_replace('=','"', str_replace("_","'", $row['poster'])))."'"; ?>
              </td>
              <td><?=  str_replace('=','"', str_replace("_","'",  htmlspecialchars($row['judul_novel']))); ?></td>
              <td><?=  htmlspecialchars($row['tahun_rilis']); ?></td>
              <td><?=  str_replace('=','"', str_replace("_","'",  htmlspecialchars($row['nama_author']))); ?></td>
              <td><?=  htmlspecialchars($row['genres']); ?></td>
              <td>
                <!-- Link untuk mengedit data novel -->
                <a href="edit_novel.php?id=<?php echo htmlspecialchars($row['id_novel']); ?>"
                  class="btn btn-sm btn-warning">Edit</a>
                <!-- Link untuk menghapus data novel -->
                <a href="delete_novel.php?id=<?php echo htmlspecialchars($row['id_novel']); ?>"
                  class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

      </div>
    </div>

    <!-- Menghubungkan Bootstrap JS, Popper.js, dan jQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
      function opennewtab(url) {
        window.open(url, '_blank').focus();
      }
    </script>
  </body>

</html>



        