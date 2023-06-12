<!DOCTYPE html>
<html>
<head>
    <title>Form Pemesanan Tiket</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
</head>
<body>
    <div class="container">
        <h2>Form Pemesanan Tiket</h2>
        <form action="process.php" method="post">
            <div class="form-group">
                <label for="id_paket">ID Paket:</label>
                <input type="text" name="id_paket" id="id_paket" required>
            </div>
            <div class="form-group">
                <label for="nama_paket">Nama Paket:</label>
                <input type="text" name="nama_paket" id="nama_paket" required>
            </div>
            <div class="form-group">
                <label for="harga_paket">Harga Paket:</label>
                <input type="number" name="harga_paket" id="harga_paket" required>
            </div>
            <div class="form-group">
                <label for="jumlah_paket">Jumlah Paket:</label>
                <input type="number" name="jumlah_paket" id="jumlah_paket" required>
            </div>
            <div class="form-group">
                <label for="metode_pembayaran">Metode Pembayaran:</label>
                <select name="metode_pembayaran" id="metode_pembayaran" required>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="Kartu Kredit">Kartu Kredit</option>
                    <option value="E-Wallet">E-Wallet</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tanggal_pemesanan">Tanggal Pemesanan:</label>
                <input type="date" name="tanggal_pemesanan" id="tanggal_pemesanan" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Insert</button>
            </div>
        </form>

        <?php
        // Menghubungkan ke MongoDB
        $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

        // Menampilkan data pemesanan
        $query = new MongoDB\Driver\Query([]);
        $rows = $mongo->executeQuery('data_pemesanan.tb_pemesanan', $query);

        echo "<h2>Data Pemesanan</h2>";
        echo "<table>";
        echo "<tr><th>ID Paket</th><th>Nama Paket</th><th>Harga Paket</th><th>Jumlah Paket</th><th>Metode Pembayaran</th><th>Tanggal Pemesanan</th><th>Action</th></tr>";
        foreach ($rows as $row) {
            echo "<tr>";
            echo "<td>" . $row->id_paket . "</td>";
            echo "<td>" . $row->nama_paket . "</td>";
            echo "<td>" . $row->harga_paket . "</td>";
            echo "<td>" . $row->jumlah_paket . "</td>";
            echo "<td>" . $row->metode_pembayaran . "</td>";
            echo "<td>" . $row->tanggal_pemesanan . "</td>";
            echo "<td><a href='updatepemesanan.php?id=" . $row->_id . "'>Update</a> | <a href='deletepemesanan.php?id=" . $row->_id . "'>Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>
    </div>
</body>
</html>
