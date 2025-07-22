<?php 
$page_title = "Confirm Order";
include 'header.php'; 

if ($user_role != 'customer' || !isset($_GET['food_id'])) {
    header("location: dashboard.php");
    exit;
}
$food_id = $_GET['food_id'];

$sql_food = "SELECT * FROM food_items WHERE id = ?";
$stmt_food = $conn->prepare($sql_food);
$stmt_food->bind_param("i", $food_id);
$stmt_food->execute();
$food_item = $stmt_food->get_result()->fetch_assoc();
if (!$food_item) { header("location: dashboard.php"); exit; }

$sql_user = "SELECT full_name, address, phone FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$customer = $stmt_user->get_result()->fetch_assoc();
?>
<main class="container">
    <div class="form-container" style="max-width: 600px;">
        <h2>Confirm Your Order</h2>
        <form action="process_order.php" method="post">
            <input type="hidden" name="food_id" value="<?php echo $food_item['id']; ?>">
            <input type="hidden" name="price" value="<?php echo $food_item['price']; ?>">
            
            <h3>Food Details</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($food_item['item_name']); ?></p>
            <p><strong>Unit Price:</strong> <?php echo htmlspecialchars($food_item['price']); ?> BDT</p>
            <hr>
            <h3>Your Information</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($customer['full_name']); ?></p>
            <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($customer['address']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($customer['phone']); ?></p>
            <hr>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" required oninput="updateTotal()">
            </div>
            <h3>Total Price: <span id="total-price"><?php echo htmlspecialchars($food_item['price']); ?></span> BDT</h3>
            <button type="submit" class="btn">Confirm & Proceed to Payment</button>
            <a href="dashboard.php" class="btn" style="background-color:#6c757d; margin-top:10px;">Cancel</a>
        </form>
    </div>
</main>
<script>
    function updateTotal() {
        let price = <?php echo $food_item['price']; ?>;
        let quantity = document.getElementById('quantity').value;
        document.getElementById('total-price').innerText = (price * quantity).toFixed(2);
    }
</script>
</body>
</html>