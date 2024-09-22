<?php
// Menyimpan informasi untuk koneksi ke server MySQL
$servername = "localhost"; // Nama server, dalam hal ini 'localhost' menunjukkan server lokal
$username = "root"; // Nama pengguna untuk login ke MySQL, dalam hal ini 'root'
$password = ""; // Kata sandi untuk login ke MySQL, dalam hal ini tidak ada kata sandi (kosong)
$dbname = "novel"; // Nama basis data yang akan diakses, dalam hal ini 'novel'

// Membuat koneksi ke database MySQL menggunakan objek mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa apakah koneksi berhasil
if ($conn->connect_error) { // Jika terjadi kesalahan koneksi
    // Menampilkan pesan kesalahan dan menghentikan eksekusi script
    die("Connection failed: " . $conn->connect_error); 
}

// Jika tidak ada kesalahan, script akan terus berjalan
?>
