<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: dataadmin.php');
    exit;
}

// Jika parameter id tidak ada, kembali ke halaman admin_dashboard.php
if (!isset($_GET['id'])) {
    header('Location: dataadmin.php');
    exit;
}

$id_paket = $_GET['id'];

// Mengecek apakah data dengan id tersebut ada di MongoDB
$filter = ['id_paket' => $id_paket];
$query = new MongoDB\Driver\Query($filter);
$result = $mongo->executeQuery('db_pakett.tb_pakett', $query);
$document = $result->toArray()[0];

// Jika data tidak ditemukan, kembali ke halaman admin_dashboard.php
if (!$document) {
    header('Location: dataadmin.php');
    exit;
}

// Jika tombol update ditekan
if (isset($_POST['update'])) {
    // Mengambil data dari form
    $nama_paket = $_POST['nama_paket'];
    $harga_paket = $_POST['harga_paket'];
    $deskripsi_paket = $_POST['deskripsi_paket'];

    // Menyiapkan data untuk diupdate di MongoDB
    $newDocument = [
        '$set' => [
            'nama_paket' => $nama_paket,
            'harga_paket' => $harga_paket,
            'deskripsi_paket' => $deskripsi_paket
        ]
    ];

    // Cek apakah file gambar terupload
    if (isset($_FILES['gambar_paket']) && $_FILES['gambar_paket']['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'images/';
        $targetFile = $targetDir . basename($_FILES['gambar_paket']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Hapus gambar lama jika ada
        if ($document->gambar_paket && file_exists($document->gambar_paket)) {
            unlink($document->gambar_paket);
        }

        // Pindahkan gambar baru ke direktori uploads
        move_uploaded_file($_FILES['gambar_paket']['tmp_name'], $targetFile);

        // Menambahkan field gambar_paket ke data update
        $newDocument['$set']['gambar_paket'] = $targetFile;
    }

    // Membuat perintah untuk update data
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->update($filter, $newDocument);

    // Menjalankan perintah update
    $mongo->executeBulkWrite('db_pakett.tb_pakett', $bulkWrite);

    // kembali ke halaman admin_dashboard.php setelah update
    header('Location: dataadmin.php');
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
        <h2>Update Data Paket</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_paket">Nama Paket:</label>
                <input type="text" name="nama_paket" id="nama_paket" value="<?php echo $document->nama_paket; ?>" required>
            </div>
            <div class="form-group">
                <label for="harga_paket">Harga Paket:</label>
                <input type="number" name="harga_paket" id="harga_paket" value="<?php echo $document->harga_paket; ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsi_paket">Deskripsi Paket:</label>
                <textarea name="deskripsi_paket" id="deskripsi_paket" required><?php echo $document->deskripsi_paket; ?></textarea>
            </div>
            <div class="form-group">
                <label for="gambar_paket">Gambar Paket:</label>
                <input type="file" name="gambar_paket" id="gambar_paket">
            </div>
            <div class="form-group">
                <button type="submit" name="update">Update</button>
            </div>
        </form>
    </div>
</body>
</html>
