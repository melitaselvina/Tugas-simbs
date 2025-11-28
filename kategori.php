<?php
session_start();
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }
    require("function.php");
    
    // Paginasi untuk data kategori
    // konfigurasi
    $jumlahDataPerHalaman = 5;
    $jumlahData = count(query("SELECT * FROM kategori"));
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
    $halamanAktif = ( isset($_GET["halaman"]) ) ? $_GET["halaman"] : 1;
    $awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;
    
    // Query untuk mengambil data kategori (Pagination + Sorting) [cite: 20, 21]
    $queryKategori = "SELECT * FROM kategori 
                      ORDER BY tanggal_input DESC 
                      LIMIT $awalData, $jumlahDataPerHalaman";

    $kategori = query($queryKategori);

    // Fitur Search [cite: 19]
    if(isset($_POST['tombol_search'])){
        $kategori = search_kategori($_POST['keyword']);
        // Setelah search, matikan pagination untuk hasil search
        $jumlahHalaman = 1;
        $halamanAktif = 1;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Kategori - SIMBS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light white bg-danger">
        <div class="container">
            <a class="navbar-brand text-white" href="#">SIMBS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link text-white" href="index.php">Data Buku</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active text-white" aria-current="page" href="kategori.php">Kategori Buku</a>
                </li>
            </ul>
            </div>
            <span class="navbar-text text-white me-3">
                Login sebagai: **<?= $_SESSION['username']?>**
            </span>
            <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        </div>
    </nav>
    <section class="p-3">
        <div class="container">
        <h1>Data Kategori Buku</h1>
            <div class="d-flex justify-content-between align-items-center">
                <a href="tambah_kategori.php">
                    <button class="mb-2 btn-sm btn-primary">Tambah Data Kategori</button> </a>
                
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                            <?php if ($halamanAktif > 1 && !isset($_POST['tombol_search'])) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?halaman=<?= $halamanAktif - 1; ?>">&laquo;</a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                            <?php if ($i == $halamanAktif) : ?>
                                <li class="page-item active">
                                    <a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a>
                                </li>
                            <?php else : ?>
                                <li class="page-item">
                                    <a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <?php if ($halamanAktif < $jumlahHalaman && !isset($_POST['tombol_search'])) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?halaman=<?= $halamanAktif + 1; ?>">&raquo;</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <form class="mb-2" action="" method="POST">
                    <div class="input-group">
                        <input type="text" class="form-control" name="keyword" placeholder="Cari kategori..." autocomplete="off">
                        <button class="btn btn-primary" type="submit" name="tombol_search">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </form>
                </div>   
            
            <table class="table table-striped table-hover">
                <tr>
                    <th>No.</th>
                    <th>Nama Kategori</th>
                    <th>Tanggal Input</th>
                    <th>Aksi</th>
                </tr>
                <?php $no = $awalData + 1; ?>
                <?php if(isset($_POST['tombol_search'])) $no = 1; ?>
                <?php foreach($kategori as $data): ?>
                <tr>
                    <td> <?= $no ?> </td>
                    <td> <?= $data['nama_kategori'] ?> </td>
                    <td> <?= $data['tanggal_input'] ?> </td>
                    <td>
                        <a href="ubah_kategori.php?id_kategori=<?= $data['id_kategori'] ?>">
                            <button class="btn-sm btn-success">Edit</button> </a>                        
                        <a href="hapus_kategori.php?id_kategori=<?= $data['id_kategori'] ?>" onclick="return confirm('Yakin ingin menghapus data ini? Semua buku dengan kategori ini mungkin akan terpengaruh.');">
                            <button class="btn-sm btn-danger">Hapus</button> </a>
                    </td>
                </tr>
                <?php $no++; ?>
                <?php endforeach; ?>
            </table>
            </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>