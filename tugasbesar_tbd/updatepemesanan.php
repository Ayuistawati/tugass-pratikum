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

    // Membuat perintah untuk update data
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->update(['id_paket' => $id_paket], ['$set' => $document]);

    // Menjalankan perintah update
    $mongo->executeBulkWrite('data_pemesanan.tb_pemesanan', $bulkWrite);
    
    // kembali ke halaman utama setelah update
    header('Location: index2.php');
    exit;
} 

// Mengambil id_paket dari parameter URL
$id_paket = $_GET['id'];

// Membuat perintah untuk mendapatkan data pemesanan berdasarkan id_paket
$query = new MongoDB\Driver\Query(['id_paket' => $id_paket]);

// Menjalankan perintah query
$cursor = $mongo->executeQuery('data_pemesanan.tb_pemesanan', $query);

// Mengambil data pemesanan
$document = current($cursor);
?>

<?php
// Menghubungkan ke MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Jika parameter id ditemukan
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Membuat filter berdasarkan id
    $filter = ['_id' => new MongoDB\BSON\ObjectID($id)];

    // Mencari data pemesanan berdasarkan id
    $query = new MongoDB\Driver\Query($filter);
    $rows = $mongo->executeQuery('data_pemesanan.tb_pemesanan', $query);
    $row = $rows->toArray()[0];

    // Menampilkan form update
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "    <title>Form Update Tiket</title>";
    echo "    <link rel='stylesheet' type='text/css' href='style2.css'>";
    echo "</head>";
    echo "<body>";
    echo "    <div class='container'>";
    echo "        <h2>Form Update Tiket</h2>";
    echo "        <form action='update_process.php?id=" . $id . "' method='post'>";
    echo "            <div class='form-group'>";
    echo "                <label for='id_paket'>ID Paket:</label>";
    echo "                <input type='text' name='id_paket' id='id_paket' value='" . $row->id_paket . "' required>";
    echo "            </div>";
    echo "            <div class='form-group'>";
    echo "                <label for='nama_paket'>Nama Paket:</label>";
    echo "                <input type='text' name='nama_paket' id='nama_paket' value='" . $row->nama_paket . "' required>";
    echo "            </div>";
    echo "            <div class='form-group'>";
    echo "                <label for='harga_paket'>Harga Paket:</label>";
    echo "                <input type='number' name='harga_paket' id='harga_paket' value='" . $row->harga_paket . "' required>";
    echo "            </div>";
    echo "            <div class='form-group'>";
    echo "                <label for='jumlah_paket'>Jumlah Paket:</label>";
    echo "                <input type='number' name='jumlah_paket' id='jumlah_paket' value='" . $row->jumlah_paket . "' required>";
    echo "            </div>";
    echo "            <div class='form-group'>";
    echo "                <label for='metode_pembayaran'>Metode Pembayaran:</label>";
    echo "                <select name='metode_pembayaran' id='metode_pembayaran' required>";
    echo "                    <option value='Transfer Bank' " . ($row->metode_pembayaran == 'Transfer Bank' ? 'selected' : '') . ">Transfer Bank</option>";
    echo "                    <option value='Kartu Kredit' " . ($row->metode_pembayaran == 'Kartu Kredit' ? 'selected' : '') . ">Kartu Kredit</option>";
    echo "                    <option value='E-Wallet' " . ($row->metode_pembayaran == 'E-Wallet' ? 'selected' : '') . ">E-Wallet</option>";
    echo "                </select>";
    echo "            </div>";
    echo "            <div class='form-group'>";
    echo "                <label for='tanggal_pemesanan'>Tanggal Pemesanan:</label>";
    echo "                <input type='date' name='tanggal_pemesanan' id='tanggal_pemesanan' value='" . $row->tanggal_pemesanan . "' required>";
    echo "            </div>";
    echo "            <div class='form-group'>";
    echo "                <button type='submit' name='submit'>Update</button>";
    echo "            </div>";
    echo "        </form>";
    echo "    </div>";
    echo "</body>";
    echo "</html>";
}
?>
