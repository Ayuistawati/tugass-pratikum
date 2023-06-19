<?php
session_start(); // Memulai session

if (isset($_GET['id'])) {
    $orderID = $_GET['id'];

    if (!empty($orderID)) { // Check if $orderID is not empty
        // Menghubungkan ke MongoDB
        $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

        // Mengambil data pemesanan berdasarkan ID
        $query = new MongoDB\Driver\Query(['_id' => new MongoDB\BSON\ObjectId($orderID)]);
        $rows = $mongo->executeQuery('data_pemesanan.tb_pemesanan', $query);

        // Memproses data pemesanan
        foreach ($rows as $row) {
            $idPaket = $row->id_paket;
            $namaPaket = $row->nama_paket;
            $hargaPaket = $row->harga_paket;
            $jumlahPaket = $row->jumlah_paket;
            $metodePembayaran = $row->metode_pembayaran;
            $tanggalKeberangkatan = $row->tanggal_keberangkatan;
        }

        // Menghitung jumlah transfer
        $jumlahTransfer = $hargaPaket * $jumlahPaket;
    } else {
        // Inisialisasi variabel agar tidak ada error
        $idPaket = "";
        $namaPaket = "";
        $hargaPaket = "";
        $jumlahPaket = "";
        $metodePembayaran = "";
        $tanggalKeberangkatan = "";
        $jumlahTransfer = "";
    }
} else {
    // Inisialisasi variabel agar tidak ada error
    $idPaket = "";
    $namaPaket = "";
    $hargaPaket = "";
    $jumlahPaket = "";
    $metodePembayaran = "";
    $tanggalKeberangkatan = "";
    $jumlahTransfer = "";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Pemesanan</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
    
</head>
<body>
    <h2>Detail Pemesanan</h2>
    <table>
        <tr>
            <th>ID Paket</th>
            <th>Nama Paket</th>
            <th>Harga Paket</th>
            <th>Jumlah Paket</th>
            <th>Metode Pembayaran</th>
            <th>Tanggal Keberangkatan</th>
            <th>Total Pembayaran</th>
        </tr>
        <tr>
            <td><?php echo $idPaket; ?></td>
            <td><?php echo $namaPaket; ?></td>
            <td><?php echo $hargaPaket; ?></td>
            <td><?php echo $jumlahPaket; ?></td>
            <td><?php echo $metodePembayaran; ?></td>
            <td><?php echo $tanggalKeberangkatan; ?></td>
            <td><?php echo $jumlahTransfer; ?></td>
        </tr>
    </table>
</body>
</html>
