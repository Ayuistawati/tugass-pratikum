<?php
if (isset($_POST['submit'])) {
    $orderID = $_POST['order_id'];

    if (!empty($orderID)) {
        // Menghubungkan ke MongoDB
        $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

        // Menyimpan data transfer ke koleksi tb_transfer
        $bulkWrite = new MongoDB\Driver\BulkWrite();
        $transferData = [
            'order_id' => new MongoDB\BSON\ObjectId($orderID),
            'id_paket' => $_POST['id_paket'],
            'nama_paket' => $_POST['nama_paket'],
            'harga_paket' => $_POST['harga_paket'],
            'jumlah_paket' => $_POST['jumlah_paket'],
            'metode_pembayaran' => $_POST['metode_pembayaran'],
            'tanggal_keberangkatan' => $_POST['tanggal_keberangkatan'],
            'nama_pengirim' => $_POST['nama_pengirim'],
            'nomor_rekening' => $_POST['nomor_rekening'],
            'jumlah_transfer' => $_POST['jumlah_transfer']
        ];
        $bulkWrite->insert($transferData);
        $mongo->executeBulkWrite('data_pemesanan.tb_transfer', $bulkWrite);

        // Menghapus data pemesanan berdasarkan ID
        $deleteFilter = ['_id' => new MongoDB\BSON\ObjectId($orderID)];
        $deleteOptions = ['limit' => 1];
        $deleteCommand = new MongoDB\Driver\BulkWrite();
        $deleteCommand->delete($deleteFilter, $deleteOptions);
        $mongo->executeBulkWrite('data_pemesanan.tb_pemesanan', $deleteCommand);

        echo "Proses transfer berhasil!";
    } else {
        echo "Terjadi kesalahan dalam memproses transfer. Order ID tidak valid.";
    }
} else {
    echo "Terjadi kesalahan dalam memproses transfer.";
}
?>
