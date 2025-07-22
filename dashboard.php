<?php 
$page_title = "Dashboard";
include 'header.php'; 
?>

<main class="container">
    <?php if(isset($_GET['food_added']) && $_GET['food_added'] == 'success'): ?>
        <div class="alert alert-success">Food item posted successfully!</div>
    <?php endif; ?>
    
    <?php if ($user_role == 'customer'): ?>
    <!-- ================================== -->
    <!--         CUSTOMER DASHBOARD         -->
    <!-- ================================== -->
    <h2>My Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order Date</th>
                <th>Food Item</th>
                <th>Prepared by</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Simplified SQL query - display all orders for customer
            $sql = "SELECT o.order_date, fi.item_name, m.full_name AS mother_name, o.total_price, o.order_status 
                    FROM orders o 
                    JOIN food_items fi ON o.food_item_id = fi.id 
                    JOIN users m ON fi.mother_id = m.id 
                    WHERE o.customer_id = ?
                    ORDER BY o.order_date DESC";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".date('d M Y', strtotime($row['order_date']))."</td>";
                    echo "<td>".htmlspecialchars($row['item_name'])."</td>";
                    echo "<td>".htmlspecialchars($row['mother_name'])."</td>";
                    echo "<td>".htmlspecialchars($row['total_price'])." BDT</td>";
                    echo "<td>".ucfirst(htmlspecialchars($row['order_status']))."</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>You have not placed any orders yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <hr>
    <h2>Order New Food</h2>
    <div class="food-items">
        <?php
        $sql_foods = "SELECT fi.id, fi.item_name, fi.price, fi.image, u.full_name as mother_name FROM food_items fi JOIN users u ON fi.mother_id = u.id WHERE fi.status = 'available'";
        $result_foods = $conn->query($sql_foods);
        if ($result_foods->num_rows > 0) {
            while($row = $result_foods->fetch_assoc()) {
                echo "<div class='food-item'>";
                echo "<img src='uploads/".htmlspecialchars($row['image'])."' alt='".htmlspecialchars($row['item_name'])."'>";
                echo "<div class='food-details'>";
                echo "<h3>".htmlspecialchars($row['item_name'])."</h3>";
                echo "<p><strong>Price:</strong> ".htmlspecialchars($row['price'])." BDT</p>";
                echo "<p><strong>By:</strong> ".htmlspecialchars($row['mother_name'])."</p>";
                echo "<a href='order.php?food_id=".$row['id']."' class='btn' style='margin-top:10px;'>Order Now</a>";
                echo "</div></div>";
            }
        } else {
            echo "<p>No food items are available at the moment.</p>";
        }
        ?>
    </div>

    <?php elseif ($user_role == 'mother'): ?>
    <!-- ================================== -->
    <!--          MOTHER DASHBOARD          -->
    <!-- ================================== -->
    <h2>Received Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Food</th>
                <th>Customer</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql_orders = "SELECT o.order_date, fi.item_name, c.full_name, c.address, c.phone, o.quantity, o.total_price, o.payment_status FROM orders o JOIN food_items fi ON o.food_item_id = fi.id JOIN users c ON o.customer_id = c.id WHERE fi.mother_id = ? ORDER BY o.order_date DESC";
            $stmt_orders = $conn->prepare($sql_orders);
            $stmt_orders->bind_param("i", $user_id);
            $stmt_orders->execute();
            $result_orders = $stmt_orders->get_result();
            if ($result_orders->num_rows > 0) {
                while($row = $result_orders->fetch_assoc()) {
                    echo "<tr><td>".date('d M Y', strtotime($row['order_date']))."</td><td>".htmlspecialchars($row['item_name'])."</td><td>".htmlspecialchars($row['full_name'])."</td><td>".htmlspecialchars($row['address'])."</td><td>".htmlspecialchars($row['phone'])."</td><td>".htmlspecialchars($row['quantity'])."</td><td>".htmlspecialchars($row['total_price'])." BDT</td><td>".ucfirst(htmlspecialchars($row['payment_status']))."</td></tr>";
                }
            } else { 
                echo "<tr><td colspan='8'>You have not received any orders yet.</td></tr>"; 
            }
            ?>
        </tbody>
    </table>
    <hr>
    <h2>Your Posted Food Items</h2>
    <table>
         <thead>
            <tr><th>Image</th><th>Name</th><th>Price</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php
            $sql_my_food = "SELECT id, item_name, price, image, status FROM food_items WHERE mother_id = ? ORDER BY posted_at DESC";
            $stmt_my_food = $conn->prepare($sql_my_food);
            $stmt_my_food->bind_param("i", $user_id);
            $stmt_my_food->execute();
            $result_my_food = $stmt_my_food->get_result();
            if ($result_my_food->num_rows > 0) {
                while($row = $result_my_food->fetch_assoc()) {
                    echo "<tr><td class='food-item-dashboard'><img src='uploads/".htmlspecialchars($row['image'])."'></td><td>".htmlspecialchars($row['item_name'])."</td><td>".htmlspecialchars($row['price'])." BDT</td><td>".ucfirst($row['status'])."</td><td>";
                    if ($row['status'] == 'available') {
                        echo "<a href='update_food_status.php?id=".$row['id']."&status=unavailable' style='color:red;'>Deactivate</a>";
                    } else {
                        echo "<a href='update_food_status.php?id=".$row['id']."&status=available' style='color:green;'>Activate</a>";
                    }
                    echo "</td></tr>";
                }
            } else { 
                echo "<tr><td colspan='5'>You have not posted any food items yet.</td></tr>"; 
            }
            ?>
        </tbody>
    </table>
    <?php endif; ?>
</main>

<footer class="site-footer">
    <div class="container">
        <h3>Our Objective</h3>
        <p>
            Our system aims to solve the food-related challenges faced by bachelor students and office workers who cannot cook for themselves. By connecting them with mothers in their locality, we provide healthy, delicious, and homemade meals filled with a mother's touch. This platform not only offers a convenient food solution for bachelors but also empowers mothers to earn extra income from the comfort of their homes.
        </p>
        <div class="footer-bottom">
            © <?php echo date("Y"); ?> Mother's Catering. All Rights Reserved.
        </div>
    </div>
</footer>

</body>
</html>
