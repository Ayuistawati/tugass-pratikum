<?php
$currentPage = 'datapemesanan';
session_start(); // Memulai session
// Menghubungkan ke MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Mengambil nilai keyword dari form
$idKeyword = isset($_POST['id_keyword']) ? $_POST['id_keyword'] : '';
$namaKeyword = isset($_POST['nama_keyword']) ? $_POST['nama_keyword'] : '';
$tanggalKeyword = isset($_POST['tanggal_keyword']) ? $_POST['tanggal_keyword'] : '';
$hargaKeyword = isset($_POST['harga_keyword']) ? $_POST['harga_keyword'] : '';

// Membuat filter berdasarkan keyword
$filter = [];
if (!empty($idKeyword)) {
    $filter[] = ['id_paket' => $idKeyword];
}
if (!empty($namaKeyword)) {
    $filter[] = ['nama_paket' => $namaKeyword];
}
if (!empty($tanggalKeyword)) {
    $filter[] = [
        'tanggal_keberangkatan' => [
            '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($tanggalKeyword)),
        ],
    ];
}
if (!empty($hargaKeyword)) {
    $filter[] = ['harga_paket' => $hargaKeyword];
}

// Menghapus data dengan tanggal pemesanan yang sudah berlalu
$deleteFilter = [
    'tanggal_keberangkatan' => [
        '$lt' => new MongoDB\BSON\UTCDateTime(time()),
    ],
];
$deleteQuery = new MongoDB\Driver\BulkWrite();
$deleteQuery->delete($deleteFilter);
$mongo->executeBulkWrite('data_pemesanan.tb_pemesanan', $deleteQuery);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data pemesanan</title>
    <link rel="stylesheet" type="text/css" href="style8.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <style>
   .container {
        margin-left: 280px; /* Lebar sidebar */
        padding: 20px;
    }
    </style> 
</head>
<body>
<?php include 'sidebaradmin.php'; ?>
    <h2>Data Pemesanan</h2>
    <div class="container">
    <div class='filter form-container'>
        <div class="filter">
            <form action="filter.php" method="POST">
                <!-- Form Filter -->
                <div class="form-group">
                    <label for="id_keyword">ID Paket:</label>
            
                    <select name="id_keyword" id="id_keyword" required>
                        <!-- Options for ID Paket -->
                        <?php
                        // Menghubungkan ke MongoDB
                        $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

                        // Menampilkan data paket wisata
                        $query = new MongoDB\Driver\Query([]);
                        $rows = $mongo->executeQuery('db_pakett.tb_pakett', $query);

                        foreach ($rows as $row) {
                            echo "<option value='" . $row->id_paket . "'>" . $row->id_paket . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nama_keyword">Nama Paket:</label>
                    <input type="text" name="nama_keyword" id="nama_keyword" readonly>
                </div>
                <div class="form-group">
                    <label for="tanggal_keyword">Tanggal Keberangkatan:</label>
                    <input type="text" name="tanggal_keyword">
                </div>
                <div class="form-group">
                    <label for="harga_keyword">Harga Paket:</label>
                    <input type="text" name="harga_keyword" id="harga_keyword" readonly>
                </div>

                <div class="form-group">
                    <input type="submit" value="Filter" class="filter-button">
                </div>
            </form>
        </div>

        <div class="export">
            <?php
            // Tombol Export dengan filter
            echo "<form action='export.php' method='POST'>";
            echo "<input type='hidden' name='id_keyword' value='" . $idKeyword . "'>";
            echo "<input type='hidden' name='nama_keyword' value='" . $namaKeyword . "'>";
            echo "<input type='hidden' name='tanggal_keyword' value='" . $tanggalKeyword . "'>";
            echo "<input type='hidden' name='harga_keyword' value='" . $hargaKeyword . "'>";
            echo "<input type='submit' name='export' value='Export' class='export-button'>";
            echo "</form>";
            ?>
        </div>
    </div>
                    </div>

    <?php
    // Menampilkan data pemesanan yang sesuai dengan filter
    $query = new MongoDB\Driver\Query([]);
    if (!empty($filter)) {
        $query = new MongoDB\Driver\Query(['$and' => $filter]);
    }

    $rows = $mongo->executeQuery('data_pemesanan.tb_pemesanan', $query);

    // Menginisialisasi variabel untuk jumlah paket terbanyak dan terkecil
    $jumlahPaketTerbanyak = 0;
    $idPaketTerbanyak = '';
    $jumlahPaketTerkecil = PHP_INT_MAX;
    $idPaketTerkecil = '';

   
    echo "<table>";
    echo "<tr><th>ID Paket</th><th>Nama Paket</th><th>Harga Paket</th><th>Jumlah Paket</th><th>Metode Pembayaran</th><th>Tanggal Keberangkatan</th><th>Total Pembayaran</th></tr>";

    $paketCounter = []; // Variabel untuk menghitung jumlah pemesanan per paket

    foreach ($rows as $row) {
        $idPaket = $row->id_paket;
        $namaPaket = $row->nama_paket;
        $hargaPaket = $row->harga_paket;
        $jumlahPaket = $row->jumlah_paket;
        $metodePembayaran = $row->metode_pembayaran;
        $tanggalKeberangkatan = $row->tanggal_keberangkatan;

        // Menghitung jumlah transfer
        $jumlahTransfer = $hargaPaket * $jumlahPaket;

        // Menambah jumlah pemesanan per paket
        if (isset($paketCounter[$idPaket])) {
            $paketCounter[$idPaket] += $jumlahPaket;
        } else {
            $paketCounter[$idPaket] = $jumlahPaket;
        }

        echo "<tr>";
        echo "<td>$idPaket</td>";
        echo "<td>$namaPaket</td>";
        echo "<td>$hargaPaket</td>";
        echo "<td>$jumlahPaket</td>";
        echo "<td>$metodePembayaran</td>";
        echo "<td>$tanggalKeberangkatan</td>";
        echo "<td>$jumlahTransfer</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Menemukan paket dengan jumlah paling banyak dipesan
    arsort($paketCounter);
    $mostOrderedPaket = key($paketCounter);
    $mostOrderedJumlah = $paketCounter[$mostOrderedPaket];

    // Menemukan paket dengan jumlah paling sedikit dipesan
    asort($paketCounter);
    $leastOrderedPaket = key($paketCounter);
    $leastOrderedJumlah = $paketCounter[$leastOrderedPaket];

   
    echo "<h4>Catatan : </h4>";
    echo "<p>Paket Paling Banyak Dipesan: ID Paket $mostOrderedPaket (Jumlah: $mostOrderedJumlah)</p>";
    echo "<p>Paket Paling Sedikit Dipesan: ID Paket $leastOrderedPaket (Jumlah: $leastOrderedJumlah)</p>";
    ?>
</body>
</html>
