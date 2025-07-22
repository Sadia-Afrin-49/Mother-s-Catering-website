<?php
include 'config.php';
if (!isset($_GET['order_id'])) { header("location: dashboard.php"); exit; }
$order_id = $_GET['order_id'];
$sql = "UPDATE orders SET payment_status = 'completed', order_status = 'confirmed' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-container" style="text-align:center;">
        <div class="alert alert-success">
            <h2>Thank You!</h2>
            <p>Your payment has been completed successfully.</p>
            <p>Your order number is: <strong><?php echo $order_id; ?></strong></p>
        </div>
        <a href="dashboard.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>