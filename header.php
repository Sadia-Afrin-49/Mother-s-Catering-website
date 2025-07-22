<?php
include_once 'config.php';

// This header is for logged-in users. Redirect if not logged in.
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
}

// Get user data from session
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title ?? "Mother's Catering"; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Header Styles */
        header { background: #e8491d; color: #fff; padding: 15px 0; border-bottom: #c33a11 3px solid; }
        header .container { display: flex; justify-content: space-between; align-items: center; margin: 0 auto; }
        header a, header li a { color: #fff; text-decoration: none; text-transform: uppercase; }
        .logo-container { display: flex; align-items: center; }
        .logo-container img { height: 50px; margin-right: 15px; }
        .logo-container h1 { font-size: 24px; margin: 0; }
        header nav ul { list-style: none; padding: 0; margin: 0; }
        header nav li { display: inline; padding: 0 15px; }

        /* Table Styles */
        table { width: 100%; border-collapse: collapse; margin: 25px 0; font-size: 14px; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; vertical-align: middle; }
        th { background-color: #f2f2f2; color: #333; }
        hr { margin: 40px 0; border: 0; border-top: 1px solid #ddd; }
        .food-item-dashboard img { max-width: 80px; height: auto; border-radius: 5px; }
        
        /* Food Grid Styles */
        .food-items { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .food-item { background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; overflow: hidden; }
        .food-item img { width: 100%; height: 160px; object-fit: cover; }
        .food-item .food-details { padding: 15px; }
        .food-item h3 { margin-top: 0; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo-container">
                <a href="dashboard.php">
                    <img src="images/logo.png" alt="Mothers Catering Logo">
                    <h1>Mother's Catering</h1>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <?php if ($user_role == 'mother'): ?>
                        <li><a href="add_food.php">Post Food</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>