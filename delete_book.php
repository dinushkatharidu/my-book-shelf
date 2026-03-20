<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare('DELETE FROM books WHERE id=?');
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php?status=deleted");
        exit();
    } else {
        echo "Error Deleting record";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
