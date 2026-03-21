<?php
include "includes/db.php";

$resTotal = $conn->query("SELECT COUNT(id) as total FROM books");
$totalBooks = $resTotal->fetch_assoc()['total'];

$resReading = $conn->query("SELECT COUNT(id) as total FROM books WHERE  status='Reading'");
$readingBooks = $resReading->fetch_assoc()['total'];

$resDone = $conn->query("SELECT COUNT(id) AS total FROM books WHERE status='Completed'");
$completedBooks = $resDone->fetch_assoc()['total'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Personal Bookshelf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #6c63ff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #5751d9;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <h2 class="text-center mb-4">My Book Shelf</h2>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white text-center p-3">
                    <h6>Total Books</h6>
                    <h3><?php echo $totalBooks; ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white text-center p-3">
                    <h6>Currently Reading</h6>
                    <h3><?php echo $readingBooks; ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white text-center p-3">
                    <h6>Completed</h6>
                    <h3><?php echo $completedBooks; ?></h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card p-4">
                    <h5 class="mb-3">Add New Book</h5>
                    <form action="add_book.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Book Title</label>
                            <input type="text" name="title" class="form-control" placeholder="The Hot Snow" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Author</label>
                            <input type="text" name="author" class="form-control" placeholder="Yuri Bondarev" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="Reading" checked>
                                <label class="form-check-label">Reading</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="Completed">
                                <label class="form-check-label">Completed</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="Want to Read">
                                <label class="form-check-label">Want to Read</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Novel">Novel</option>
                                <option value="Tech">Tech</option>
                                <option value="History">History</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <button type="submit" name="save_book" class="btn btn-primary w-100">Add to Shelf</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">

                <div class="card p-3 mb-3 shadow-sm">
                    <form action="index.php" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control border-0 bg-light"
                                placeholder="Search by Title or Author..."
                                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                            <button class="btn btn-primary" type="submit">Search</button>
                            <?php if (isset($_GET['search'])): ?>
                                <a href="index.php" class="btn btn-outline-secondary">Clear</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="col-md-12">
                    <div class="card p-4">
                        <h5 class="mb-3">My Library</h5>
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                include 'includes/db.php';
                                $search = isset($_GET['search']) ? $_GET['search'] : '';

                                if ($search != '') {
                                    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY id DESC");
                                    $searchTearm = "%$search%";
                                    $stmt->bind_param("ss", $searchTearm, $searchTearm);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                } else {
                                    $sql = "SELECT * FROM books ORDER BY id DESC";
                                    $result = $conn->query($sql);
                                }



                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $statusColor = "secondary";
                                        if ($row['status'] == 'Reading') $statusColor = "info text-dark";
                                        if ($row['status'] == 'Completed') $statusColor = "success";
                                        if ($row['status'] == 'Want to Read') $statusColor = "warning text-dark";

                                        echo "<tr>";
                                        echo "<td><strong>" . $row['title'] . "</strong></td>";
                                        echo "<td>" . $row['author'] . "</td>";
                                        echo "<td><span class='badge bg-$statusColor'>" . $row['status'] . "</span></td>";
                                        echo "<td>
                                                <a href='edit_book.php?id=" . $row['id'] . "' class='btn btn-sm btn-outline-warning'>Edit</a>
                                                <a href='delete_book.php?id=" . $row['id'] . "' class='btn btn-sm btn-outline-danger' onclick=\"return confirm('Delete this book?')\">Delete</a>
                                         </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center text-muted'>No books on your shelf yet.</td></tr>";
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>