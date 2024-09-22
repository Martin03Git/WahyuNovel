<?php
// Mengimpor file konfigurasi database
require_once("config.php");

// Memeriksa apakah tombol register telah diklik
if (isset($_POST['register'])) {
    // Filter data yang diinputkan untuk mencegah serangan XSS
    $name = htmlspecialchars($_POST['fullname']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST["password"]);
    $level = "user"; // Menetapkan level pengguna sebagai 'user'
    
    // Validasi input untuk memastikan tidak ada yang kosong
    if (empty($name) || empty($username) || empty($password)) {
        // Mengalihkan ke halaman register dengan pesan error jika ada input yang kosong
        header("Location: register.php?pesan=gagal&reason=empty");
        exit();
    }

    // Menyiapkan query untuk memeriksa apakah username sudah ada di database
    $checkSql = "SELECT * FROM user WHERE username = ?";
    $checkStmt = $conn->prepare($checkSql); // Menggunakan prepared statement untuk keamanan
    $checkStmt->bind_param("s", $username); // Mengikat parameter ke query
    $checkStmt->execute(); // Menjalankan query
    $result = $checkStmt->get_result(); // Mendapatkan hasil query

    // Jika username sudah ada, mengalihkan kembali ke halaman register dengan pesan error
    if ($result->num_rows > 0) {
        header("Location: register.php?pesan=gagal&reason=user_exists");
        exit();
    }

    // Menyiapkan query untuk menyimpan user baru ke database
    $sql = "INSERT INTO user (nama_user, username, password, level) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql); // Menggunakan prepared statement untuk keamanan

    // Mengikat parameter ke query
    $stmt->bind_param("ssss", $name, $username, $password, $level);

    // Menjalankan query untuk menyimpan ke database
    $saved = $stmt->execute();

    // Jika query simpan berhasil, mengalihkan ke halaman login
    if ($saved) {
        header("Location: index.php");
        exit();
    } else {
        // Jika terjadi kesalahan pada penyimpanan, mengalihkan ke halaman register dengan pesan error
        header("Location: register.php?pesan=gagal&reason=database");
        exit();
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WahyuFilm | Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    body {
      padding: 0;
    }

    div.container-fluid {
      height: 100vh;
      width: 100vw;
      background-color: #F5C518;
      color: #F5C518;
      display: flex;
      justify-content: center;
      align-items: center;
    }


    /* movie poster */
    div.movie-poster {
      padding: 0;
    }

    div.movie-poster img {
      width: 100%;
      padding: 0;
      border-radius: 5px 0 0 5px;
    }


    /* input section */
    form {
      background-color: #fff;
      width: 600px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      box-shadow: 0px 0px 7px 0px #94874e;
    }

    h1 {
      text-align: center;
      font-weight: 700;
    }

    div.btn-wrap {
      display: flex;
      justify-content: center;
      color: #fff;
      font-weight: 700;
    }


    /* pesan ketika gagal login */
    .alert-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      height: 100vh;
      position: absolute;
      z-index: 999;
    }

    .alert{
      background: #e44e4e;
      color: white;
      padding: 10px;
      text-align: center;
      border:1px solid #b32929;
    }

  </style>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <div class="container-fluid">
  <?php 
    // Memeriksa apakah parameter 'pesan' ada di URL
    if (isset($_GET['pesan'])) {
        // Memeriksa apakah nilai dari 'pesan' adalah "gagal"
        if ($_GET['pesan'] == "gagal") {
            // Mendapatkan alasan kegagalan dari parameter 'reason' atau menetapkan 'unknown' jika tidak ada
            $reason = $_GET['reason'] ?? 'unknown';
            // Membuat pesan dasar untuk kegagalan
            $message = "Gagal mendaftar. ";
            
            // Menentukan pesan spesifik berdasarkan alasan kegagalan
            if ($reason == 'empty') {
                $message .= "Harap mengisi semua field."; // Pesan jika ada field yang kosong
            } elseif ($reason == 'database') {
                $message .= "Terjadi kesalahan pada database."; // Pesan jika terjadi kesalahan pada database
            } else {
                $message .= "Terjadi kesalahan yang tidak diketahui."; // Pesan untuk alasan yang tidak diketahui
            }
            
            // Menampilkan pesan alert dalam div dengan kelas 'alert-wrap' dan 'alert'
            echo "<div class='alert-wrap'><div class='alert'>{$message}</div></div>";
        }
    }
  ?>


    <form class="row" action="" method="post">
      <div class="movie-poster col-6">
        <img src="images/My Divine Diary.jpg" alt="">
      </div>
      <div class="input-section col-6 p-2">
        <h1 class="mb-4">Register</h1>
        <div class="input-group flex-nowrap mb-2">
          <span class="input-group-text" id="addon-wrapping">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
              <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 
              4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
            </svg>
          </span>
          <input name="fullname" type="text" class="form-control" placeholder="Fullname" aria-label="Fullname" aria-describedby="addon-wrapping">
        </div>
        <div class="input-group flex-nowrap mb-2">
          <span class="input-group-text" id="addon-wrapping">@</span>
          <input name="username" type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
        </div>
        <div class="input-group flex-nowrap mb-2">
          <span class="input-group-text" id="addon-wrapping">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
              <path 
                d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 
                8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
            </svg>
          </span>
          <input name="password" type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="addon-wrapping">
        </div>
        <small><b>Sudah punya akun? Login <a href="index.php">disini</a></b></small>
        <div class="btn-wrap mt-2">
          <button type="submit" class="btn btn-warning" name="register">Submit</button>
        </div>
      </div>
    </form>
  </div>

  <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
    crossorigin="anonymous"></script>
  <script>
    $('.alert-wrap').on("click", function() { $(this).remove() });
  </script>
</body>
</html>
