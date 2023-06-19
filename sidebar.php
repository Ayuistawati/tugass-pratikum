<style>
    .sidebar {
        background-image: url(images/kelingking.jpeg); /* Ganti dengan URL gambar latar belakang yang diinginkan */
        background-size: cover;
        padding: 20px;
        width: 230px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1; /* Tambahkan z-index agar sidebar berada di atas konten */
    }

    .sidebar ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .sidebar li {
        margin-bottom: 10px;
    }

    .sidebar a {
        color: #fff;
        text-decoration: none;
        display: block;
        text-align: center;
    }

    .sidebar a.active {
        font-weight: bold;
    }
    
    h1 {
        margin-bottom: 70px;
        font-family: 'Times New Roman', Times, serif;
        font-size: 25px;
    }
    
    h3 {
        background-color: blue;
        padding: 15px;
    }
    
    .rounded-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
    }

  
</style>


<div class="sidebar">
    <ul>
        <li><a href="" <?php if ($currentPage === 'dashboard') echo 'class="active"'; ?>><img src="images/logo.png" alt="" width="100px" class="rounded-image"><h1>BALITRAVEL</h1></a></li>
        <li><a href="user_dashboard.php" <?php if ($currentPage === 'profil') echo 'class="active"'; ?>><h3>Profil</h3></a></li>
        <li><a href="datauser.php" <?php if ($currentPage === 'beranda') echo 'class="active"'; ?>><h3>Beranda</h3></a></li>
        <li><a href="index2.php" <?php if ($currentPage === 'pemesanan') echo 'class="active"'; ?>><h3>Pemesanan</h3></a></li>
        <li><a href="index.php" <?php if ($currentPage === 'log') echo 'class="active"'; ?>><h3>Log out</h3></a></li>
    </ul>
</div>


