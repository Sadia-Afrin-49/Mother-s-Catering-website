<?php 
$page_title = "My Profile";
include 'header.php'; 

$error = '';
$success = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- SERVER-SIDE VALIDATION START ---
    // trim() function removes any accidental spaces from start/end
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check if any of the required fields are empty after trimming
    if (empty($full_name) || empty($phone) || empty($address)) {
        $error = "Name, Phone, and Address fields cannot be empty.";
    } else {
        // If all fields are valid, proceed with the database update
        $sql = "UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $full_name, $phone, $address, $user_id);

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            $_SESSION['user_name'] = $full_name; // Update session name to show new name instantly
        } else {
            $error = "Error updating profile. Please try again.";
        }
        $stmt->close();
    }
    // --- SERVER-SIDE VALIDATION END ---
}

// Fetch the user's current data to display in the form
$sql_user = "SELECT full_name, email, phone, address, user_role FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();
?>

<main class="container">
    <div class="form-container" style="max-width: 600px; margin: 50px auto;">
        <h2>My Profile</h2>
        <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
        
        <form action="profile.php" method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email (Cannot be changed)</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly style="background-color: #eee;">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Role</label>
                <input type="text" value="<?php echo ucfirst($user['user_role']); ?>" readonly style="background-color: #eee;">
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>
    </div>
</main>
</body>
</html>