<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin.php');
    exit;
}

// Fungsi untuk mendapatkan data admin berdasarkan username
function getAdminData($username)
{
    global $mongo;

    $query = new MongoDB\Driver\Query(['username' => $username, 'role' => 'admin']);
    $result = $mongo->executeQuery('db_logreg.tb_register', $query)->toArray();

    if (!empty($result)) {
        return $result[0];
    } else {
        return null;
    }
}

// Fungsi untuk mengupdate data admin
function updateAdminData($username, $data)
{
    global $mongo;

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->update(['username' => $username, 'role' => 'admin'], ['$set' => $data]);

    $mongo->executeBulkWrite('db_logreg.tb_register', $bulkWrite);
}

// Fungsi untuk menghapus data admin
function deleteAdminData($username)
{
    global $mongo;

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->delete(['username' => $username, 'role' => 'admin']);

    $mongo->executeBulkWrite('db_logreg.tb_register', $bulkWrite);
}

// Memperbarui data admin
if (isset($_POST['update'])) {
    $username = $_SESSION['username'];
    $newPassword = $_POST['password'];
    $newGmail = $_POST['gmail'];
    $newAddress = $_POST['alamat'];
    $newPhone = $_POST['no_tlp'];

    $data = [
        'password' => $newPassword,
        'gmail' => $newGmail,
        'alamat' => $newAddress,
        'no_tlp' => $newPhone
    ];

    updateAdminData($username, $data);
}

// Menghapus data admin
if (isset($_POST['delete'])) {
    $username = $_SESSION['username'];
    deleteAdminData($username);

    session_unset();
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Mendapatkan data admin yang sedang login
$username = $_SESSION['username'];
$adminData = getAdminData($username);

if (!$adminData) {
    // Jika data admin tidak ditemukan, redirect ke halaman login
    header('Location: admin.php');
    exit;
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
        <h2>Welcome, <?php echo $username; ?> (Admin)</h2>
        <h3>Profile</h3>
        <table>
            <tr>
                <td>Username:</td>
                <td><?php echo $adminData->username; ?></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><?php echo $adminData->password; ?></td>
            </tr>
            <tr>
                <td>Gmail:</td>
                <td><?php echo $adminData->gmail; ?></td>
            </tr>
            <tr>
                <td>Alamat:</td>
                <td><?php echo $adminData->alamat; ?></td>
            </tr>
            <tr>
                <td>No. Telepon:</td>
                <td><?php echo $adminData->no_tlp; ?></td>
            </tr>
        </table>

        <h3>Edit Profile</h3>
        <form action="" method="post">
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="text" name="password" id="password" value="<?php echo $adminData->password; ?>">
            </div>
            <div class="form-group">
                <label for="gmail">Gmail:</label>
                <input type="text" name="gmail" id="gmail" value="<?php echo $adminData->gmail; ?>">
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <input type="text" name="alamat" id="alamat" value="<?php echo $adminData->alamat; ?>">
            </div>
            <div class="form-group">
                <label for="no_tlp">No. Telepon:</label>
                <input type="text" name="no_tlp" id="no_tlp" value="<?php echo $adminData->no_tlp; ?>">
            </div>
            <div class="form-group">
                <button type="submit" name="update">Update Profile</button>
            </div>
        </form>

        <h3>Delete Account</h3>
        <form action="" method="post">
            <div class="form-group">
                <button type="submit" name="delete">Delete Account</button>
            </div>
        </form>
    </div>
</body>
</html>
