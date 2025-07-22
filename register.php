<?php
include 'config.php';
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $user_role = $_POST['user_role'];

    $check_sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error = "This email is already registered.";
    } else {
        $sql = "INSERT INTO users (full_name, email, password, phone, address, user_role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $full_name, $email, $password, $phone, $address, $user_role);
        if ($stmt->execute()) {
            $success = "Registration successful! Redirecting to login page in 3 seconds...";
            header("Refresh: 3; url=login.php");
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Mother's Catering</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="centered-form">
    <div class="form-container">
        <h2>Create an Account</h2>
        <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>

        <?php if(empty($success)): ?>
        <form action="register.php" method="post">
            <div class="form-group"><label>Full Name</label><input type="text" name="full_name" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Phone Number</label><input type="text" name="phone" required></div>
            <div class="form-group"><label>Address</label><textarea name="address" required></textarea></div>
            <div class="form-group">
                <label>Register as</label>
                <select name="user_role" required>
                    <option value="customer">Customer</option>
                    <option value="mother">Mother</option>
                </select>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p style="text-align:center; margin-top:20px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
        <?php endif; ?>
    </div>
</body>
</html>