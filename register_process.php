<?php
require_once 'config.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gmail = $_POST['gmail'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];
    $role = $_POST['role'];

    $document = [
        'username' => $username,
        'password' => $password,
        'gmail' => $gmail,
        'alamat' => $alamat,
        'no_tlp' => $no_tlp,
        'role' => $role
    ];

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($document);
   

// Tambahkan properti gambar ke dokumen
    $document['gambar'] = $binaryData;

    $mongo->executeBulkWrite('db_logreg.tb_register', $bulkWrite);
    
    header('Location: index.php');
    exit;
}
?>
