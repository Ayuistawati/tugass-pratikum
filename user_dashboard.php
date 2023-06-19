;
<?
$_SESSION['username'] = $username; // Menyimpan nilai username ke dalam session
?>

<?php
$currentPage = 'profil';
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
    $newPassword = $_POST['password'];
    $newGmail = $_POST['gmail'];
    $newAddress = $_POST['alamat'];
    $newPhone = $_POST['no_tlp'];

    $data = [
        'password'  => $newPassword,
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
<?php include 'sidebar.php'; ?>
    <div class="container">
        <h2>Welcome, <?php echo $username; ?> (User)</h2>
        <h3>Profile</h3>
        <table>
            <tr>
                <td>Username:</td>
                <td><?php echo $userData->username; ?></td>
            </tr>
            <tr>
                <td>password:</td>
                <td><?php echo $userData->password; ?></td>
            </tr>
            <tr>
                <td>Gmail:</td>
                <td><?php echo $userData->gmail; ?></td>
            </tr>
            <tr>
                <td>Alamat:</td>
                <td><?php echo $userData->alamat; ?></td>
            </tr>
            <tr>
                <td>No. Telepon:</td>
                <td><?php echo $userData->no_tlp; ?></td>
            </tr>
        </table>

        <h3>Edit Profile</h3>
        <form action="" method="post">
            <div class="form-group">
                <label for="alamat">password</label>
                <input type="text" name="password" id="password" value="<?php echo $userData->password; ?>">
            </div>
            <div class="form-group">
                <label for="alamat">gmail:</label>
                <input type="text" name="gmail" id="gmail" value="<?php echo $userData->gmail; ?>">
            </div>
            <div class="form-group">
                <label for="no_tlp">alamat:</label>
                <input type="text" name="alamat" id="alamat" value="<?php echo $userData->alamat; ?>">
            </div>
            <div class="form-group">
                <label for="no_tlp">No. Telepon:</label>
                <input type="text" name="no_tlp" id="no_tlp" value="<?php echo $userData->no_tlp; ?>">
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
</div>
		</div>

  </body>
</html>
