<?php
include '../config.php';

$id = $_GET['id'];

$sql = "DELETE FROM author WHERE id_author=$id";
if ($conn->query($sql) === TRUE) {
    header('Location: manage_author.php');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
