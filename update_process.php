<?php
// Menghubungkan ke MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Jika parameter id ditemukan dan tombol submit ditekan
if (isset($_GET['id']) && isset($_POST['submit'])) {
    $id = $_GET['id'];

    // Membuat filter berdasarkan id
    $filter = ['_id' => new MongoDB\BSON\ObjectID($id)];

    // Mengambil data dari form
    $id_paket = $_POST['id_paket'];
    $nama_paket = $_POST['nama_paket'];
    $harga_paket = $_POST['harga_paket'];
    $jumlah_paket = $_POST['jumlah_paket'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $tanggal_pemesanan = $_POST['tanggal_pemesanan'];

    // Menyiapkan data untuk diupdate ke MongoDB
    $document = [
        'id_paket' => $id_paket,
        'nama_paket' => $nama_paket,
        'harga_paket' => $harga_paket,
        'jumlah_paket' => $jumlah_paket,
        'metode_pembayaran' => $metode_pembayaran,
        'tanggal_pemesanan' => $tanggal_pemesanan
    ];

    // Membuat perintah untuk update data
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->update($filter, ['$set' => $document]);

    // Menjalankan perintah update
    $mongo->executeBulkWrite('data_pemesanan.tb_pemesanan', $bulkWrite);
    
    // Kembali ke halaman utama setelah update
    header('Location: index2.php');
    exit;
}
?>
