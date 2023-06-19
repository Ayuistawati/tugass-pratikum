<!DOCTYPE html>
<html>
<head>
    <title>Data pemesanan</title>
    <link rel="stylesheet" type="text/css" href="style8.css">
</head>
<body>
<?php
session_start();

// Menghubungkan ke MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Mengambil nilai keyword dari form
$idKeyword = $_POST['id_keyword'];
$namaKeyword = $_POST['nama_keyword'];
$tanggalKeyword = $_POST['tanggal_keyword'];
$hargaKeyword = $_POST['harga_keyword'];

// Membuat filter berdasarkan keyword
$filter = [];
if (!empty($idKeyword)) {
    $filter[] = ['id_paket' => $idKeyword];
}
if (!empty($namaKeyword)) {
    $filter[] = ['nama_paket' => $namaKeyword];
}
if (!empty($tanggalKeyword)) {
    $filter[] = ['tanggal_keberangkatan' => $tanggalKeyword];
}
if (!empty($hargaKeyword)) {
    $filter[] = ['harga_paket' => $hargaKeyword];
}

// Menampilkan data pemesanan yang sesuai dengan filter
$query = new MongoDB\Driver\Query([]);
if (!empty($filter)) {
    $query = new MongoDB\Driver\Query(['$and' => $filter]);
}

$rows = $mongo->executeQuery('data_pemesanan.tb_pemesanan', $query);

echo "<h2>Data Pemesanan</h2>";
echo "<table>";
echo "<tr><th>ID Paket</th><th>Nama Paket</th><th>Harga Paket</th><th>Jumlah Paket</th><th>Metode Pembayaran</th><th>Tanggal Pemesanan</th><th>Total Pembayaran</th></tr>";
foreach ($rows as $row) {
    echo "<tr>";
    echo "<td>" . $row->id_paket . "</td>";
    echo "<td>" . $row->nama_paket . "</td>";
    echo "<td>" . $row->harga_paket . "</td>";
    echo "<td>" . $row->jumlah_paket . "</td>";
    echo "<td>" . $row->metode_pembayaran . "</td>";
    echo "<td>" . $row->tanggal_keberangkatan . "</td>";
    echo "<td>" . ($row->harga_paket * $row->jumlah_paket) . "</td>";
    echo "</tr>";
}
echo "</table>";

// Tombol Export dengan filter
echo "<form action='export.php' method='POST'>";
echo "<input type='hidden' name='id_keyword' value='" . $idKeyword . "'>";
echo "<input type='hidden' name='nama_keyword' value='" . $namaKeyword . "'>";
echo "<input type='hidden' name='tanggal_keyword' value='" . $tanggalKeyword . "'>";
echo "<input type='hidden' name='harga_keyword' value='" . $hargaKeyword . "'>";
echo "<input type='submit' name='export' value='Export' class='export-button'>";
echo "</form>";

?>
</body>
</html>
