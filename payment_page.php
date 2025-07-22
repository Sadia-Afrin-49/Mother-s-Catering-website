<?php
include 'config.php';
if (!isset($_GET['order_id'])) { header("location: dashboard.php"); exit; }
$order_id = $_GET['order_id'];
$sql = "SELECT total_price FROM orders WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make Payment</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-container" style="text-align: center;">
        <img src="https://logowik.com/content/uploads/images/bkash-new-logo-20232152.logowik.com.webp" alt="bKash" width="150">
        <h2>Complete Your Payment</h2>
        <h3>Total Amount: <?php echo $order['total_price']; ?> BDT</h3>
        <p>This is a simulated payment page. In a real application, you would be redirected to the bKash gateway.</p>
        <br>
        <a href="payment_success.php?order_id=<?php echo $order_id; ?>" class="btn" style="background-color: #e2136e;">Pay <?php echo $order['total_price']; ?> BDT Now</a>
    </div>
</body>
</html>