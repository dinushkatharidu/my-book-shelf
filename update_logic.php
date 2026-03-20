<?php
include 'includes/db.php';

if (isset($_POST['update_book'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE books SET title=? , author=?, category=?, status=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $author, $category, $status, $id);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php?status=updated");
        exit();
    } else {
        echo "Error updating Record " . $conn->error;
        exit();
    }
} else {
    header("Location: index.php?");
    exit();
}
