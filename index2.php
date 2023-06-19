<?php
$currentPage = 'pemesanan';
// Menghubungkan ke MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");



// Menghapus data pemesanan jika tanggal keberangkatan sudah lewat
$deleteFilter = ['tanggal_keberangkatan' => ['$lt' => new MongoDB\BSON\UTCDateTime(time() * 1000)]];
$deleteOptions = ['limit' => 0]; // Menghapus semua data yang memenuhi kriteria
$deleteCommand = new MongoDB\Driver\BulkWrite();
$deleteCommand->delete($deleteFilter, $deleteOptions);
$mongo->executeBulkWrite('data_pemesanan.tb_pemesanan', $deleteCommand);

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
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Pemesanan Tiket</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#id_paket').change(function() {
                var idPaket = $(this).val();
                $.ajax({
                    url: 'get_nama_harga_paket.php',
                    type: 'POST',
                    data: {id_paket: idPaket},
                    success: function(response) {
                        var data = JSON.parse(response);
                        $('#nama_paket').val(data.nama_paket);
                        $('#harga_paket').val(data.harga_paket);
                    }
                });
            });
        });
    </script>

<style>
    .container {
        margin-left: 280px; /* Lebar sidebar */
        padding: 20px;
    }
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="container">
        <h2>Form Pemesanan Tiket</h2>
        <form action="process.php" method="post">
        <div class="form-group">
                    <label for="id_paket">ID Paket:</label>
                    <select name="id_paket" id="id_paket" required>
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
                    <label for="nama_paket">Nama Paket:</label>
                    <input type="text" name="nama_paket" id="nama_paket" readonly>
                </div>
                <div class="form-group">
                    <label for="harga_paket">Harga Paket:</label>
                    <input type="number" name="harga_paket" id="harga_paket" readonly>
                </div>
                <div class="form-group">
                    <label for="jumlah_paket">Jumlah Paket:</label>
                    <input type="number" name="jumlah_paket" id="jumlah_paket" required>
                </div>
                <div class="form-group">
                    <label for="metode_pembayaran">Metode Pembayaran:</label>
                    <select name="metode_pembayaran" id="metode_pembayaran" required>
                        <!-- Options for Metode Pembayaran -->
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Kartu Kredit">Kartu Kredit</option>
                        <option value="E-Wallet">E-Wallet</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggal_keberangkatan">Tanggal Keberangkatan:</label>
                    <input type="date" name="tanggal_keberangkatan" id="tanggal_keberangkatan" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="submit">Booking</button>
                </div>
        </form>
    </div>

    <div class="container">
        <?php
        // Menampilkan data pemesanan
        $query = new MongoDB\Driver\Query([]);
        $rows = $mongo->executeQuery('data_pemesanan.tb_pemesanan', $query);

        echo "<h2>Data Pemesanan</h2>";
        echo "<table>";
        echo "<tr><th>ID Paket</th><th>Nama Paket</th><th>Harga Paket</th><th>Jumlah Paket</th><th>Metode Pembayaran</th><th>Tanggal Keberangkatan</th><th>Action</th></tr>";
        foreach ($rows as $row) {
            $tanggalKeberangkatan = strtotime($row->tanggal_keberangkatan);
            if ($tanggalKeberangkatan >= time()) {
            echo "<tr>";
            echo "<td>" . $row->id_paket . "</td>";
            echo "<td>" . $row->nama_paket . "</td>";
            echo "<td>" . $row->harga_paket . "</td>";
            echo "<td>" . $row->jumlah_paket . "</td>";
            echo "<td>" . $row->metode_pembayaran . "</td>";
            echo "<td>" . date('Y-m-d', $tanggalKeberangkatan) . "</td>";
            echo "<td><a href='updatepemesanan.php?id=" . $row->_id . "'>Edit</a> | <a href='deletepemesanan.php?id=" . $row->_id . "'>Delete</a> | <a href='book.php?id=" . $row->_id . "'>Detail</a> </td>";
            echo "</tr>";
        }
    }
        echo "</table>";
        ?>
    </div>
</body>
</html>
