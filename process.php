<?php
session_start(); // Memulai session

if (isset($_POST['submit'])) {
    // Menghubungkan ke MongoDB
    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

    // Mendapatkan data dari form pemesanan
    $idPaket = $_POST['id_paket'];
    $namaPaket = $_POST['nama_paket'];
    $hargaPaket = $_POST['harga_paket'];
    $jumlahPaket = $_POST['jumlah_paket'];
    $metodePembayaran = $_POST['metode_pembayaran'];
    $tanggalKeberangkatan = $_POST['tanggal_keberangkatan'];

    // Menyimpan data pemesanan ke koleksi tb_pemesanan
    $bulkWrite = new MongoDB\Driver\BulkWrite();
    $pemesananData = [
        'id_paket' => $idPaket,
        'nama_paket' => $namaPaket,
        'harga_paket' => $hargaPaket,
        'jumlah_paket' => $jumlahPaket,
        'metode_pembayaran' => $metodePembayaran,
        'tanggal_keberangkatan' => $tanggalKeberangkatan
    ];
    $bulkWrite->insert($pemesananData);
    $mongo->executeBulkWrite('data_pemesanan.tb_pemesanan', $bulkWrite);

    // Mengambil ID pemesanan terakhir yang di-generate oleh MongoDB
    $orderID = $pemesananData['_id'];

    // Redirect ke halaman book.php dengan menyertakan ID pemesanan
    header("Location: book.php?id=" . $orderID);
    exit();
}
?>
