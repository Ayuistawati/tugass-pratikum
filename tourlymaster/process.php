<?php
// Menghubungkan ke MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {
    // Mengambil data dari form
    $id_paket = $_POST['id_paket'];
    $nama_paket = $_POST['nama_paket'];
    $harga_paket = $_POST['harga_paket'];
    $jumlah_paket = $_POST['jumlah_paket'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $tanggal_pemesanan = $_POST['tanggal_pemesanan'];

    // Menyiapkan data untuk dimasukkan ke MongoDB
    $document = [
        'id_paket' => $id_paket,
        'nama_paket' => $nama_paket,
        'harga_paket' => $harga_paket,
        'jumlah_paket' => $jumlah_paket,
        'metode_pembayaran' => $metode_pembayaran,
        'tanggal_pemesanan' => $tanggal_pemesanan
    ];

    // Membuat perintah untuk insert data
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($document);

    // Menjalankan perintah insert
    $mongo->executeBulkWrite('data_pemesanan.tb_pemesanan', $bulkWrite);
    
    // Redirect ke halaman utama setelah submit
    header('Location: index2.php');
    exit;
} 
?>
