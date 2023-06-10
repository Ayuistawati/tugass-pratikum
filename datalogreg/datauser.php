<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Fungsi untuk mendapatkan data pengguna berdasarkan username
function getUserData($username)
{
    global $mongo;

    $query = new MongoDB\Driver\Query(['username' => $username]);
    $result = $mongo->executeQuery('db_logreg.tb_register', $query)->toArray();

    if (!empty($result)) {
        return $result[0];
    } else {
        return null;
    }
}

// Fungsi untuk mengupdate data pengguna
function updateUserData($username, $data)
{
    global $mongo;

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->update(['username' => $username], ['$set' => $data]);

    $mongo->executeBulkWrite('db_logreg.tb_register', $bulkWrite);
}

// Fungsi untuk menghapus data pengguna
function deleteUserData($username)
{
    global $mongo;

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->delete(['username' => $username]);

    $mongo->executeBulkWrite('db_logreg.tb_register', $bulkWrite);
}

// Memperbarui data pengguna
if (isset($_POST['update'])) {
    $username = $_SESSION['username'];
    $newGmail = $_POST['gmail'];
    $newAddress = $_POST['alamat'];
    $newPhone = $_POST['no_tlp'];

    $data = [
        'gmail'  => $newGmail,
        'alamat' => $newAddress,
        'no_tlp' => $newPhone
    ];

    updateUserData($username, $data);
}

// Menghapus data pengguna
if (isset($_POST['delete'])) {
    $username = $_SESSION['username'];
    deleteUserData($username);

    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

// Mendapatkan data pengguna yang sedang login
$username = $_SESSION['username'];
$userData = getUserData($username);

if (!$userData) {
    // Jika data pengguna tidak ditemukan, redirect ke halaman login
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $username; ?> (User)</h2>
       <a href="user_dashboard.php">data user</a>
    </div>
</body>
</html>
