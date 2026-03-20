<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare('SELECT * FROM books WHERE id=?');
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $author = $row['author'];
        $category = $row['category'];
        $status = $row['status'];
    } else {
        echo "No data found";
        exit();
    }

    $stmt->close();
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Book | LitTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4" style="border-radius: 15px;">
                    <h4 class="mb-4 text-center">Edit Book Details</h4>

                    <form action="update_logic.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">

                        <div class="mb-3">
                            <label class="form-label">Book Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Author</label>
                            <input type="text" name="author" class="form-control" value="<?php echo $author; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Novel" <?php if ($category == 'Novel') echo 'selected'; ?>>Novel</option>
                                <option value="Tech" <?php if ($category == 'Tech') echo 'selected'; ?>>Tech</option>
                                <option value="History" <?php if ($category == 'History') echo 'selected'; ?>>History</option>
                                <option value="Other" <?php if ($category == 'Other') echo 'selected'; ?>>Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="Reading" <?php if ($status == 'Reading') echo 'checked'; ?>>
                                <label class="form-check-label">Reading</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="Completed" <?php if ($status == 'Completed') echo 'checked'; ?>>
                                <label class="form-check-label">Completed</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="Want to Read" <?php if ($status == 'Want to Read') echo 'checked'; ?>>
                                <label class="form-check-label">Want to Read</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="update_book" class="btn btn-primary">Update Book</button>
                            <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>