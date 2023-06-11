
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style4.css">
</head>
<body>
    <div class="container">
        <h2>Welcome</h2>
    
        <?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: datauser.php');
    exit;
}

// Fungsi untuk mendapatkan semua data paket wisata
function getAllPackages()
{
    global $mongo;

    $query = new MongoDB\Driver\Query([]);
    $result = $mongo->executeQuery('db_logreg.tb_logreg', $query)->toArray();

    return $result;
}

// Mendapatkan semua data paket wisata
$packages = getAllPackages();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Welcome, User!</h1>

    <!-- Daftar Paket Wisata -->
    <h2>List of Packages</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama Paket</th>
            <th>Harga Paket</th>
            <th>Deskripsi Paket</th>
            <th>Gambar Paket</th>
        </tr>
        <?php foreach ($packages as $package) : ?>
            <tr>
                <td><?php echo $package->_id; ?></td>
                <td><?php echo $package->nama_paket; ?></td>
                <td><?php echo $package->harga_paket; ?></td>
                <td><?php echo $package->deskripsi_paket; ?></td>
                <td><img src="<?php echo $package->gambar_paket; ?>" width="100"></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>


       <a href="user_dashboard.php">data user</a>
    </div>
</body>
</html>
