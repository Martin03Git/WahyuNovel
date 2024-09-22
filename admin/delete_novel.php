<?php
include '../config.php';
$id = $_GET['id'];

// Menghapus data dari tabel novel_genre terlebih dahulu
$sql_delete_genre = "DELETE FROM novel_genre WHERE id_novel=$id";
$sql_delete_rate = "DELETE FROM rating WHERE id_novel=$id";
if ($conn->query($sql_delete_genre) === TRUE AND $conn->query($sql_delete_rate) === TRUE ) {
    // Menghapus data dari tabel novel setelah berhasil menghapus dari tabel novel_genre
    $sql = "DELETE FROM novel WHERE id_novel=$id";
    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql_delete_genre . "<br>" . $conn->error;
}

$conn->close();
?>