<?php
include '../config.php';
$id = $_GET['id'];

// Menghapus data dari tabel rating terlebih dahulu
$sql_delete_rate = "DELETE FROM rating WHERE id_user=$id";
if ($conn->query($sql_delete_rate) === TRUE ) {
    // Menghapus data dari tabel user setelah berhasil menghapus dari tabel rating
    $sql = "DELETE FROM user WHERE id_user=$id";
    if ($conn->query($sql) === TRUE) {
        header('Location: manage_user.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql_delete_genre . "<br>" . $conn->error;
}

$conn->close();
?>