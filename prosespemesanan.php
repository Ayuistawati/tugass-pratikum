<?php
session_start(); // Memulai session

// Menghubungkan ke MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Query untuk menampilkan pesanan yang diproses
$currentDate = new MongoDB\BSON\UTCDateTime(time() * 1000);
$queryProcessed = new MongoDB\Driver\Query(['tanggal_keberangkatan' => ['$gte' => $currentDate]]);
$rowsProcessed = $mongo->executeQuery('data_pemesanan.tb_pemesanan', $queryProcessed);

// Query untuk menampilkan riwayat pemesanan
$queryHistory = new MongoDB\Driver\Query(['tanggal_keberangkatan' => ['$lt' => $currentDate]]);
$rowsHistory = $mongo->executeQuery('data_pemesanan.tb_pemesanan', $queryHistory);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Proses Pemesanan</title>
    <style>
        body {
            background-image: url("background.jpg");
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #333;
        }

        tr:nth-child(even) {
            background-color: #555;
        }
    </style>
</head>
<body>
    <h2>Pesanan yang Diproses</h2>
    <table>
        <tr>
            <th>ID Paket</th>
            <th>Nama Paket</th>
            <th>Harga Paket</th>
            <th>Jumlah Paket</th>
            <th>Metode Pembayaran</th>
            <th>Tanggal Keberangkatan</th>
            <th>Action</th>
        </tr>
        <?php foreach ($rowsProcessed as $row) : ?>
            <tr>
                <td><?php echo $row->id_paket; ?></td>
                <td><?php echo $row->nama_paket; ?></td>
                <td><?php echo $row->harga_paket; ?></td>
                <td><?php echo $row->jumlah_paket; ?></td>
                <td><?php echo $row->metode_pembayaran; ?></td>
                <td><?php echo $row->tanggal_keberangkatan; ?></td>
                <td>
                    <a href="tiket.php?id=<?php echo $row->_id; ?>">Tiket</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Riwayat Pemesanan</h2>
    <table>
        <tr>
            <th>ID Paket</th>
            <th>Nama Paket</th>
            <th>Harga Paket</th>
            <th>Jumlah Paket</th>
            <th>Metode Pembayaran</th>
            <th>Tanggal Keberangkatan</th>
            <th>Action</th>
        </tr>
        <?php foreach ($rowsHistory as $row) : ?>
            <tr>
                <td><?php echo $row->id_paket; ?></td>
                <td><?php echo $row->nama_paket; ?></td>
                <td><?php echo $row->harga_paket; ?></td>
                <td><?php echo $row->jumlah_paket; ?></td>
                <td><?php echo $row->metode_pembayaran; ?></td>
                <td><?php echo $row->tanggal_keberangkatan; ?></td>
                <td>
                    <a href="tiket.php?id=<?php echo $row->_id; ?>">Tiket</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
