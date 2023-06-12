<!-- admin_dashboard.php -->
<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: dataadmin.php');
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
        <!-- Data List -->
        <h3>Data List</h3>
        <?php
        // Query untuk mendapatkan data dari MongoDB
        $query = new MongoDB\Driver\Query([]);
        $result = $mongo->executeQuery('db_logreg.tb_register', $query);

        echo '<table>';
        echo '<tr><th>Username</th><th>Password</th><th>Gmail</th><th>Alamat</th><th>No. Telepon</th><th>Role</th><th>Action</th></tr>';
        foreach ($result as $row) {
            echo '<tr>';
            echo '<td>' . $row->username . '</td>';
            echo '<td>' . $row->password . '</td>';
            echo '<td>' . $row->gmail . '</td>';
            echo '<td>' . $row->alamat . '</td>';
            echo '<td>' . $row->no_tlp . '</td>';
            echo '<td>' . $row->role . '</td>';
            echo '<td>';
            echo '<a href="update.php?id=' . $row->_id . '">Update</a>';
            echo ' | ';
            echo '<a href="delete.php?id=' . $row->_id . '">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
        ?>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
