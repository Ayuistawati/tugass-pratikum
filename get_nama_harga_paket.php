<?php
// Menghubungkan ke MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

if(isset($_POST['id_paket'])){
    $idPaket = $_POST['id_paket'];

    // Membuat filter berdasarkan id_paket yang diterima
    $filter = ['id_paket' => $idPaket];
    $options = [];
    $query = new MongoDB\Driver\Query($filter, $options);

    // Mengambil data paket wisata berdasarkan id_paket
    $rows = $mongo->executeQuery('db_pakett.tb_pakett', $query);

    // Memeriksa apakah ada hasil query
    if($rows->isDead()){
        echo json_encode(['error' => 'Data paket tidak ditemukan']);
    } else {
        foreach($rows as $row){
            $data = [
                'nama_paket' => $row->nama_paket,
                'harga_paket' => $row->harga_paket
            ];
            echo json_encode($data);
        }
    }
}
?>
