<?php
session_start();

include 'includes/db.php';

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id , username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "User not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LitTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .auth-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .center-screen {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>

    <div class="container center-screen">
        <div class="col-md-4">
            <div class="card auth-card p-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">Book Shelf</h2>
                    <p class="text-muted">Welcome back! Please login.</p>
                </div>

                <?php if (isset($error)) echo "<div class='alert alert-danger py-2 small'>$error</div>"; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" name="login" class="btn btn-primary fw-bold p-2">Login to Shelf</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="small">Don't have an account? <a href="register.php" class="text-decoration-none">Create one</a></p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>