<?php
$currentPage = 'dashboard';
session_start();
require_once 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: maindashboarduser.php');
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

// Mendapatkan semua data paket wisata
$packages = getAllPackages();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style4.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
    <h1>Daftar Paket</h1>
    <!-- Daftar Paket Wisata -->
    <table>
        <tr>
            <th>ID Paket</th>
            <th>Nama Paket</th>
            <th>Harga Paket</th>
            <th>Deskripsi Paket</th>
            <th>Gambar Paket</th>
        </tr>
        <?php foreach ($packages as $package) : ?>
            <tr>
                <td><?php echo $package->id_paket; ?></td>
                <td><?php echo $package->nama_paket; ?></td>
                <td><?php echo $package->harga_paket; ?></td>
                <td><?php echo $package->deskripsi_paket; ?></td>
                <td><img src="<?php echo $package->gambar_paket; ?>" width="300"></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>


