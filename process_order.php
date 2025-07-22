<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer' || $_SERVER["REQUEST_METHOD"] != "POST") {
    header("location: login.php");
    exit;
}

$customer_id = $_SESSION['user_id'];
$food_id = $_POST['food_id'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$total_price = $quantity * $price;

$sql = "INSERT INTO orders (customer_id, food_item_id, quantity, total_price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiid", $customer_id, $food_id, $quantity, $total_price);
if ($stmt->execute()) {
    $order_id = $conn->insert_id;
    header("location: payment_page.php?order_id=" . $order_id);
    exit;
} else {
    echo "Error processing your order. Please try again.";
}
?>