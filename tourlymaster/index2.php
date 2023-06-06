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
                <label for="id_paket">id Paket:</label>
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
                <button type="submit" name="submit">Booking</button>
            </div>
        </form>
    </div>
</body>
</html>
