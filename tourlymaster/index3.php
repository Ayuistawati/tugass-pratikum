<!DOCTYPE html>
<html>
<head>
    <title>Paket Wisata</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="number"] {
            width: 100%;
            padding: 5px;
        }

        .form-group input[type="submit"] {
            background-color: #263c85;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
    // Menghubungkan ke MongoDB
    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

    // Fungsi untuk mendapatkan data paket wisata dari MongoDB
    function getPaketWisata()
    {
        global $mongo;

        $query = new MongoDB\Driver\Query([]);
        $rows = $mongo->executeQuery('PaketWisata.paket_wisataa', $query);

        return $rows;
    }

    // Fungsi untuk menambahkan paket wisata baru ke MongoDB
    function tambahPaketWisata($Id_PaketWisata, $Nama_PaketWisata, $Destinasi_PaketWisata, $Harga_PaketWisata)
    {
        global $mongo;

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert(['Id_PaketWisata' => $Id_PaketWisata, 'Nama_PaketWisata' => $Nama_PaketWisata, 'Destinasi_PaketWisata' => $Destinasi_PaketWisata, 'Harga_PaketWisata' => $Harga_PaketWisata]);
        $mongo->executeBulkWrite('PaketWisata.paket_wisataa', $bulk);
    }

    // Fungsi untuk menghapus paket wisata dari MongoDB berdasarkan _id
    function hapusPaketWisata($id)
    {
        global $mongo;

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete(['_id' => new MongoDB\BSON\ObjectID($id)]);
        $mongo->executeBulkWrite('PaketWisata.paket_wisataa', $bulk);
    }

    // Memproses form tambah paket wisata
    if (isset($_POST['tambah'])) {
        $Id_PaketWisata = $_POST['Id_PaketWisata'];
        $Nama_PaketWisata = $_POST['Nama_PaketWisata'];
        $Destinasi_PaketWisata = $_POST['Destinasi_PaketWisata'];
        $Harga_PaketWisata = $_POST['Harga_PaketWisata'];

        tambahPaketWisata($Id_PaketWisata, $Nama_PaketWisata, $Destinasi_PaketWisata, $Harga_PaketWisata);
    }

    // Memproses form hapus paket wisata
    if (isset($_POST['hapus'])) {
        $id = $_POST['hapus'];

        hapusPaketWisata($id);
    }
    ?>

    <h1>Daftar Paket Wisata</h1>

    <form method="post">
        <div class="form-group">
            <label for="Id_PaketWisata">Id_PaketWisata</label>
            <input type="text" id="Id_PaketWisata" name="Id_PaketWisata" required>
        </div>
        <div class="form-group">
            <label for="Nama_PaketWisata">Nama_PaketWisata</label>
            <input type="text" id="Nama_PaketWisata" name="Nama_PaketWisata" required>
        </div>
        <div class="form-group">
            <label for="Destinasi_PaketWisata">Destinasi_PaketWisata</label>
            <input type="text" id="Destinasi_PaketWisata" name="Destinasi_PaketWisata" required>
        </div>
        <div class="form-group">
            <label for="Harga_PaketWisata">Harga_PaketWisata</label>
            <input type="number" id="Harga_PaketWisata" name="Harga_PaketWisata" required>
        </div>
        <div class="form-group">
            <input type="submit" name="tambah" value="Tambah Paket">
        </div>
    </form>

    <table>
        <tr>
            <th>Id_PaketWisata</th>
            <th>Nama_PaketWisata</th>
            <th>Destinasi_PaketWisata</th>
            <th>Harga_PaketWisata</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Menampilkan data paket wisata
        $paketWisata = getPaketWisata();
        foreach ($paketWisata as $paket) {
            echo '<tr>';
            echo '<td>' . $paket->Id_PaketWisata . '</td>';
            echo '<td>' . $paket->Nama_PaketWisata . '</td>';
            echo '<td>' . $paket->Destinasi_PaketWisata . '</td>';
            echo '<td>' . $paket->Harga_PaketWisata . '</td>';
            echo '<td>';
            echo '<form method="post">';
            echo '<input type="hidden" name="hapus" value="' . $paket->_id . '">';
            echo '<input type="submit" value="Hapus">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</body>
</html>
