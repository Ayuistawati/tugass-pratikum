<?php
// Menghubungkan ke MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Mengambil nilai filter dari form
$filter = json_decode($_POST['filter'], true);

// Membuat filter berdasarkan keyword
$filter = [];
if (!empty($idKeyword)) {
    $filter[] = ['id_paket' => $idKeyword];
}
if (!empty($namaKeyword)) {
    $filter[] = ['nama_paket' => $namaKeyword];
}
if (!empty($tanggalKeyword)) {
    $filter[] = ['tanggal_pemesanan' => $tanggalKeyword];
}
if (!empty($hargaKeyword)) {
    $filter[] = ['harga_paket' => $hargaKeyword];
}

// ...

// Menampilkan data pemesanan yang sesuai dengan filter
$query = new MongoDB\Driver\Query(['$and' => $filter]);
$rows = $mongo->executeQuery('data_pemesanan.tb_pemesanan', $query);

// Tombol Export
echo "<form action='export.php' method='POST'>";
echo "<input type='hidden' name='filter' value='" . json_encode($filter) . "'>";
echo "<input type='submit' name='export' value='Export'>";
echo "</form>";
?>

// Membuat dokumen CSV dari data yang difilter
$filename = 'data_pemesanan_filtered.csv';
$file = fopen($filename, 'w');
$header = ['ID Paket', 'Nama Paket', 'Harga Paket', 'Jumlah Paket', 'Metode Pembayaran', 'Tanggal Pemesanan'];
fputcsv($file, $header);
foreach ($rows as $row) {
    $data = [$row->id_paket, $row->nama_paket, $row->harga_paket, $row->jumlah_paket, $row->metode_pembayaran, $row->tanggal_pemesanan];
    fputcsv($file, $data);
}
fclose($file);

// Menyajikan dokumen CSV untuk diunduh
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
readfile($filename);
unlink($filename);
?>
