<!-- delete.php -->
<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Jika parameter id tidak ada, kembali ke halaman admin_dashboard.php
if (!isset($_GET['id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$id = $_GET['id'];

// Mengecek apakah data dengan id tersebut ada di MongoDB
$filter = ['_id' => new MongoDB\BSON\ObjectID($id)];
$query = new MongoDB\Driver\Query($filter);
$result = $mongo->executeQuery('db_logreg.tb_register', $query);
$document = $result->toArray()[0];

// Jika data tidak ditemukan, kembali ke halaman admin_dashboard.php
if (!$document) {
    header('Location: admin_dashboard.php');
    exit;
}

// Jika tombol delete ditekan
if (isset($_POST['delete'])) {
    // Membuat perintah untuk delete data
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->delete($filter);

    // Menjalankan perintah delete
    $mongo->executeBulkWrite('db_logreg.tb_register', $bulkWrite);

    // kembali ke halaman admin_dashboard.php setelah delete
    header('Location: admin_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Data</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Delete Data</h2>
        <p>Are you sure you want to delete this data?</p>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo $document->username; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="gmail">Gmail:</label>
                <input type="email" name="gmail" id="gmail" value="<?php echo $document->gmail; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <input type="text" name="alamat" id="alamat" value="<?php echo $document->alamat; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="no_tlp">No. Telepon:</label>
                <input type="text" name="no_tlp" id="no_tlp" value="<?php echo $document->no_tlp; ?>" readonly>
            </div>
            <div class="form-group">
                <button type="submit" name="delete">Delete</button>
            </div>
        </form>
    </div>
</body>
</html>
