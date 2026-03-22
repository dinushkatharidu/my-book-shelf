<?php
include 'includes/db.php';

if (isset($_POST['save_book'])) {

    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO books(title, author, category, status) VALUES(?, ?, ?, ?) ");
    $stmt->bind_param("ssss", $title, $author, $category, $status);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php?status=added");
        exit();
    } else {
        header("Location: index.php?status=error");
        exit();
    }
}
