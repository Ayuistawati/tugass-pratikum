<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: dataadmin.php');
    exit;
}

// Mengambil data pengguna dari MongoDB
$query = new MongoDB\Driver\Query([]);
$result = $mongo->executeQuery('db_logreg.tb_register', $query);

// Mengatur pilihan combo box
$options = [
    'all' => 'All Users',
    'admin' => 'Admins',
    'user' => 'Users'
];

// Mendapatkan data berdasarkan pilihan combo box
if (isset($_GET['role'])) {
    $selectedRole = $_GET['role'];
    if ($selectedRole !== 'all') {
        $query = new MongoDB\Driver\Query(['role' => $selectedRole]);
        $result = $mongo->executeQuery('db_logreg.tb_register', $query);
    }
} else {
    $selectedRole = 'all';
}
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
        
        <!-- Combo Box -->
        <form action="" method="get">
            <label for="role">Select Role:</label>
            <select name="role" id="role">
                <?php
                foreach ($options as $value => $label) {
                    $selected = ($selectedRole === $value) ? 'selected' : '';
                    echo "<option value=\"$value\" $selected>$label</option>";
                }
                ?>
            </select>
            <button type="submit">Filter</button>
        </form>
        
        <!-- Data List -->
        <h3>Data List</h3>
        <table>
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Gmail</th>
                <th>Alamat</th>
                <th>No. Telepon</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php
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
            ?>
        </table>
        
        <a href="maindashboardadmin.php">Return</a>
    </div>
</body>
</html>
