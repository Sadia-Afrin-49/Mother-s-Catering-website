<?php
include 'config.php';

// Ensure user is a logged-in mother
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'mother') {
    header("location: login.php");
    exit;
}

// Check if required parameters are set
if (isset($_GET['id']) && isset($_GET['status'])) {
    $food_id = $_GET['id'];
    $new_status = $_GET['status'];
    $mother_id = $_SESSION['user_id'];

    // Security Check: Make sure this mother owns the food item
    $check_sql = "SELECT id FROM food_items WHERE id = ? AND mother_id = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ii", $food_id, $mother_id);
    $stmt_check->execute();
    
    if ($stmt_check->get_result()->num_rows == 1) {
        // If owner is correct, update the status
        $update_sql = "UPDATE food_items SET status = ? WHERE id = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("si", $new_status, $food_id);
        $stmt_update->execute();
    }
}
// Redirect back to dashboard regardless of outcome
header("location: dashboard.php");
exit;
?>