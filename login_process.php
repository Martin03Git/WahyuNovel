<?php 
// Mengaktifkan session pada PHP
session_start();
 
// Menghubungkan PHP dengan file konfigurasi database
include 'config.php';
 
// Menangkap data yang dikirim dari form login
$username = $_POST['username']; // Menyimpan data username yang dikirim melalui metode POST
$password = $_POST['password']; // Menyimpan data password yang dikirim melalui metode POST
 
// Menyeleksi data user dengan username dan password yang sesuai dari tabel user
$login = mysqli_query($conn,"SELECT * FROM user WHERE username='$username' AND password='$password'");
// Menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($login);
 
// Cek apakah username dan password ditemukan pada database
if($cek > 0){ // Jika ditemukan data yang sesuai
 
    $data = mysqli_fetch_assoc($login); // Mengambil data user dari hasil query
    
    // Cek jika user login sebagai admin
    if($data['level']=="admin"){
        // Membuat session login dan username
        $_SESSION['username'] = $username;
        $_SESSION['level'] = "admin";
        // Mengalihkan ke halaman dashboard admin
        header("location:./admin/index.php");
    
    // Cek jika user login sebagai user
    } else if($data['level']=="user") {
        // Membuat session login dan username
        $_SESSION['username'] = $username;
        $_SESSION['level'] = "user";
        // Mengalihkan ke halaman dashboard user
        header("location:./pengguna/index.php");

    } else {
        // Mengalihkan ke halaman login kembali dengan pesan 'gagal'
        header("location:index.php?pesan=gagal");
    }    
} else {
    // Jika username dan password tidak ditemukan, mengalihkan ke halaman login kembali dengan pesan 'gagal'
    header("location:index.php?pesan=gagal");
}
 
?>
