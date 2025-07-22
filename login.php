<?php
include 'config.php';
$error = '';

if (isset($_SESSION['user_id'])) {
    header("location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, full_name, password, user_role FROM users WHERE email = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['user_role'];
                header("location: dashboard.php");
                exit;
            } else { $error = "Incorrect password."; }
        } else { $error = "No account found with that email."; }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Mother's Catering</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="centered-form">
    <div class="form-container">
        <h2>Login</h2>
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p style="text-align:center; margin-top:20px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</body>
</html>