<!-- update.php -->
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

// Jika tombol update ditekan
if (isset($_POST['update'])) {
    // Mengambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gmail = $_POST['gmail'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];

    // Menyiapkan data untuk diupdate di MongoDB
    $newDocument = [
        '$set' => [
            'username' => $username,
            'password' => $password,
            'gmail' => $gmail,
            'alamat' => $alamat,
            'no_tlp' => $no_tlp
        ]
    ];

    // Membuat perintah untuk update data
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->update($filter, $newDocument);

    // Menjalankan perintah update
    $mongo->executeBulkWrite('db_logreg.tb_register', $bulkWrite);

    // kembali ke halaman admin_dashboard.php setelah update
    header('Location: admin_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Data</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Update Data</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo $document->username; ?>" required>
            </div>
            <div class="form-group">
                <label for="gmail">password:</label>
                <input type="text" name="password" id="password" value="<?php echo $document->password; ?>" required>
            </div>
            <div class="form-group">
                <label for="gmail">Gmail:</label>
                <input type="email" name="gmail" id="gmail" value="<?php echo $document->gmail; ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <input type="text" name="alamat" id="alamat" value="<?php echo $document->alamat; ?>" required>
            </div>
            <div class="form-group">
                <label for="no_tlp">No. Telepon:</label>
                <input type="text" name="no_tlp" id="no_tlp" value="<?php echo $document->no_tlp; ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" name="update">Update</button>
            </div>
        </form>
    </div>
</body>
</html>
