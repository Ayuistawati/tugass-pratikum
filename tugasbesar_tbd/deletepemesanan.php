<?php
// Menghubungkan ke MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Jika parameter id ditemukan
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Membuat filter berdasarkan id
    $filter = ['_id' => new MongoDB\BSON\ObjectID($id)];

    // Membuat perintah untuk delete data
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->delete($filter);

    // Menjalankan perintah delete
    $mongo->executeBulkWrite('data_pemesanan.tb_pemesanan', $bulkWrite);

    // Kembali ke halaman utama setelah delete
    header('Location: index2.php');
    exit;
}
?>
