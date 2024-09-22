<?php
    include '../config.php';
    $id_genre = $_GET['id'];

    // Query untuk menghapus genre berdasarkan id_genre
    $sql = "DELETE FROM genre WHERE id_genre=$id_genre";

    // Menambahkan query untuk menghapus data terkait dari tabel 'novel_genre' sebelum menghapus dari tabel 'genre'
    $sql_delete_related_data = "DELETE FROM novel_genre WHERE id_genre='$id_genre'";

    // Memulai transaksi
    $conn->begin_transaction();

    if ($conn->query($sql_delete_related_data) === TRUE) {
        if ($conn->query($sql) === TRUE) {
        // Jika berhasil, commit transaksi dan redirect ke halaman manage_genre.php
        $conn->commit();
        header('Location: manage_genre.php');
        } else {
            // Jika terjadi error saat menghapus dari tabel 'genre', rollback transaksi dan tampilkan pesan error
            $conn->rollback();
            echo "Error: " . $sql . "<br>" . $conn->error;
        } 
    } else {
        // Jika terjadi error saat menghapus data terkait dari tabel 'rating', tampilkan pesan error
        echo "Error: " . $sql_delete_related_data . "<br>" . $conn->error;
    }

    // Menutup koneksi database
    $conn->close();
?>