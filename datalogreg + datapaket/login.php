<?php
session_start();
require_once 'config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $filter = ['username' => $username, 'password' => $password, 'role' => $role];
    $query = new MongoDB\Driver\Query($filter);
    $result = $mongo->executeQuery('db_logreg.tb_register', $query)->toArray();

    if (!empty($result)) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        
        if ($role == 'admin') {
            header('Location: dataadmin.php');
            exit;
        } elseif ($role == 'user') {
            header('Location: datauser.php');
            exit;
        }
    } else {
        echo "Login failed. Invalid credentials.";
    }
}
?>
