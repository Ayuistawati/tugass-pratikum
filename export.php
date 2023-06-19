<?php
// Menghubungkan ke MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Mengambil nilai keyword dari form (jika ada)
$idKeyword = $_POST['id_keyword'];
$namaKeyword = $_POST['nama_keyword'];
$tanggalKeyword = $_POST['tanggal_keyword'];
$hargaKeyword = $_POST['harga_keyword'];

// Membuat filter berdasarkan keyword (jika ada)
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
// Array header kolom
$headers = ['ID Paket', 'Nama Paket', 'Harga Paket', 'Jumlah Paket', 'Metode Pembayaran', 'Tanggal Keberangkatan'];

// Array data pemesanan
$data = [];
foreach ($rows as $row) {
    $tanggal = date('Y-m-d', strtotime($row->tanggal_keberangkatan));
    
    $data[] = [
        $row->id_paket,
        $row->nama_paket,
        $row->harga_paket,
        $row->jumlah_paket,
        $row->metode_pembayaran,
        $tanggal
    ];
}

// Membuat file CSV
$filename = 'data_pemesanan.csv';
$file = fopen($filename, 'w');

// Menulis header kolom ke file
fputcsv($file, $headers);

// Menulis data pemesanan ke file
foreach ($data as $row) {
    fputcsv($file, $row);
}

// Menutup file
fclose($file);

// Mengatur header untuk mengunduh file CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename=' . $filename);
header('Pragma: no-cache');
header('Expires: 0');

// Mengirim file ke output
readfile($filename);

// Menghapus file CSV setelah dikirim
unlink($filename);
?>
