<?php 
$page_title = "Post New Food";
include 'header.php';

// Only mothers can access this page
if ($user_role != 'mother') {
    header("location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0755, true); }
        $image_name = uniqid() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO food_items (mother_id, item_name, description, price, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isdss", $user_id, $item_name, $description, $price, $image_name);
            if ($stmt->execute()) {
                header("location: dashboard.php?food_added=success");
                exit; 
            } else {
                $error = "Error posting food item: " . $stmt->error;
            }
        } else { $error = "Sorry, there was an error uploading your file."; }
    } else { $error = "Please select an image file."; }
}
?>
<main class="container">
    <div class="form-container" style="max-width: 600px;">
        <h2>Add a New Food Item</h2>
        <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <form action="add_food.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Food Name</label>
                <input type="text" name="item_name" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="form-group">
                <label>Price (in BDT)</label>
                <input type="number" name="price" step="1" required>
            </div>
            <div class="form-group">
                <label>Food Image</label>
                <input type="file" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn">Post Item</button>
        </form>
    </div>
</main>
</body>
</html>