<?php
include 'includes/db.php';

if (isset($_POST['signup'])) {
    $user = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (? ,?) ");
    $stmt->bind_param("ss", $user, $hashed_password);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Registration successful! <a href='login.php'>Login here</a></div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | LitTrack</title>
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
                    <h2 class="fw-bold text-success">Join Book Shelf</h2>
                    <p class="text-muted">Start tracking your reading journey today.</p>
                </div>

                <?php if (isset($msg)) echo $msg; ?>

                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Choose a Username</label>
                        <input type="text" name="username" class="form-control" placeholder="e.g. dinushka" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Create Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" name="signup" class="btn btn-success fw-bold p-2">Create My Account</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="small">Already have an account? <a href="login.php" class="text-decoration-none">Login here</a></p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>