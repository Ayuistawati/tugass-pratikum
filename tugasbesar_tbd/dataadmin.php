 <?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: dataadmin.php');
    exit;
}

// Fungsi untuk mendapatkan semua data paket wisata
function getAllPackages()
{
    global $mongo;

    $query = new MongoDB\Driver\Query([]);
    $result = $mongo->executeQuery('db_pakett.tb_pakett', $query)->toArray();

    return $result;
}

// Fungsi untuk menambahkan paket wisata baru
function addPackage($nama_paket, $harga_paket, $deskripsi_paket, $gambar_paket)
{
    global $mongo;

    $document = [
        'nama_paket' => $nama_paket,
        'harga_paket' => $harga_paket,
        'deskripsi_paket' => $deskripsi_paket,
        'gambar_paket' => $gambar_paket
    ];

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($document);

    $mongo->executeBulkWrite('db_pakett.tb_pakett', $bulkWrite);
}

// Fungsi untuk mengupdate paket wisata
function updatePackage($id_paket, $nama_paket, $harga_paket, $deskripsi_paket)
{
    global $mongo;

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->update(
        ['_id' => new MongoDB\BSON\ObjectId($id_paket)],
        ['$set' => [
            'nama_paket' => $nama_paket,
            'harga_paket' => $harga_paket,
            'deskripsi_paket' => $deskripsi_paket
        ]]
    );

    $mongo->executeBulkWrite('db_pakett.tb_pakett', $bulkWrite);
}

// Fungsi untuk menghapus paket wisata
function deletePackage($id_paket)
{
    global $mongo;

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->delete(['_id' => new MongoDB\BSON\ObjectId($id_paket)]);

    $mongo->executeBulkWrite('db_pakett.tb_pakett', $bulkWrite);
}

// Jika tombol submit (insert) ditekan
if (isset($_POST['submit'])) {
    $nama_paket = $_POST['nama_paket'];
    $harga_paket = $_POST['harga_paket'];
    $deskripsi_paket = $_POST['deskripsi_paket'];

    $gambar_paket = $_FILES['gambar_paket']['name'];
    $gambar_paket_tmp = $_FILES['gambar_paket']['tmp_name'];
    $gambar_paket_path = 'images/' . $gambar_paket;

    move_uploaded_file($gambar_paket_tmp, $gambar_paket_path);

    addPackage($nama_paket, $harga_paket, $deskripsi_paket, $gambar_paket_path);
}

// Jika tombol submit (update) ditekan
if (isset($_POST['update'])) {
    // Mengambil data dari form
    $update_id = $_POST['update_id'];
    $nama_paket = $_POST['nama_paket'];
    $harga_paket = $_POST['harga_paket'];
    $deskripsi_paket = $_POST['deskripsi_paket'];

    // Memeriksa apakah ada file gambar yang diunggah
    if ($_FILES['gambar_paket']['name'] !== '') {
        $gambar_paket = $_FILES['gambar_paket']['name'];
        $temp_name = $_FILES['gambar_paket']['tmp_name'];
        $folder = "uploads/";
        move_uploaded_file($temp_name, $folder . $gambar_paket);
    } else {
        // Jika tidak ada file gambar diunggah, tetap menggunakan gambar yang sudah ada sebelumnya
        $gambar_paket = $_POST['gambar_paket_old'];
    }

    // Menyiapkan data untuk diupdate
    $update_data = [
        'nama_paket' => $nama_paket,
        'harga_paket' => $harga_paket,
        'deskripsi_paket' => $deskripsi_paket,
        'gambar_paket' => $gambar_paket
    ];

    // Membuat perintah untuk update data
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->update(['_id' => new MongoDB\BSON\ObjectId($update_id)], ['$set' => $update_data]);

    // Menjalankan perintah update
    $mongo->executeBulkWrite('db_pakett.tb_pakett', $bulkWrite);

    // Redirect kembali ke halaman admin_dashboard.php setelah proses update selesai
    header('Location: dataadmin.php');
    exit;
}

// Jika tombol submit (delete) ditekan
if (isset($_POST['delete'])) {
    $id_paket = $_POST['id_paket'];

    deletePackage($id_paket);
}

// Mendapatkan semua data paket wisata
$packages = getAllPackages();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style4.css">
</head>
<body>
    <h1>Tambah Paket</h1>

    <!-- Form Insert Paket -->
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama_paket">Nama Paket:</label>
            <input type="text" name="nama_paket" id="nama_paket" required>
        </div>
        <div class="form-group">
            <label for="harga_paket">Harga Paket:</label>
            <input type="number" name="harga_paket" id="harga_paket" required>
        </div>
        <div class="form-group">
            <label for="deskripsi_paket">Deskripsi Paket:</label>
            <textarea name="deskripsi_paket" id="deskripsi_paket" required></textarea>
        </div>
        <div class="form-group">
            <label for="gambar_paket">Gambar Paket:</label>
            <input type="file" name="gambar_paket" id="gambar_paket" required>
        </div>
        <div class="form-group">
            <button type="submit" name="submit">Add</button>
        </div>
    </form>


    <!-- Daftar Paket Wisata -->
    <table>
        <tr>
            <th>ID</th>
            <th>Nama Paket</th>
            <th>Harga Paket</th>
            <th>Deskripsi Paket</th>
            <th>Gambar Paket</th>
            <th>Action</th>
        </tr>
        <?php foreach ($packages as $package) : ?>
            <tr>
                <td><?php echo $package->_id; ?></td>
                <td><?php echo $package->nama_paket; ?></td>
                <td><?php echo $package->harga_paket; ?></td>
                <td><?php echo $package->deskripsi_paket; ?></td>
                <td><img src="<?php echo $package->gambar_paket; ?>" width="300"></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="id_paket" value="<?php echo $package->_id; ?>">
                        <a href="updatepaket.php?id=<?php echo $package->_id; ?>" class="button">Update</a>
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>


        
