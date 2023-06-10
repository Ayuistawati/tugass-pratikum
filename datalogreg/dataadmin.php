<!-- admin_dashboard.php -->
<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// ...

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?> (Admin)</h2>
    <a href=""></a>
        
<a href="admin_dashboard.php">data admin</a>
      
    </div>
</body>
</html>
