<?php
session_start();
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }
    require("function.php");
    
    // Paginasi untuk data buku
    // konfigurasi
    $jumlahDataPerHalaman = 5;
    // Menggunakan JOIN untuk menampilkan nama kategori
    $queryCount = "SELECT buku.*, kategori.nama_kategori 
                   FROM buku
                   LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori
                   ORDER BY tanggal_input DESC";
    $jumlahData = count(query($queryCount));
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
    $halamanAktif = ( isset($_GET["halaman"]) ) ? $_GET["halaman"] : 1;
    $awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;
    
    // Query untuk mengambil data buku (Pagination + Sorting) [cite: 28]
    $queryBuku = "SELECT buku.*, kategori.nama_kategori 
                  FROM buku 
                  LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori
                  ORDER BY tanggal_input DESC 
                  LIMIT $awalData, $jumlahDataPerHalaman";

    $buku = query($queryBuku);

    // Fitur Search tidak ada di spesifikasi halaman buku, tetapi dipertahankan jika ada kebutuhan di masa depan
    // if(isset($_POST['tombol_search'])){
    //     $buku = search_data($_POST['keyword']); // Anda perlu membuat fungsi search_buku() jika ingin mengaktifkan
    // }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Buku - SIMBS</title>
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
                <a class="nav-link active text-white" aria-current="page" href="index.php">Data Buku</a>
                </li>
                <li class="nav-item">
                <a class="nav-link text-white" href="kategori.php">Kategori Buku</a>
                </li>
            </ul>
            </div>
            <span class="navbar-text text-white me-3">
                Login sebagai: <?= $_SESSION['username']?>
            </span>
            <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        </div>
    </nav>
    <section class="p-3">
        <div class="container">
        <h1>Data Buku</h1>
            <div class="d-flex justify-content-between align-items-center">
                <a href="tambah_buku.php">
                    <button class="mb-2 btn-sm btn-primary">Tambah Data Buku</button> </a>
                
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                            <?php if ($halamanAktif > 1) : ?>
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
                        <?php if ($halamanAktif < $jumlahHalaman) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?halaman=<?= $halamanAktif + 1; ?>">&raquo;</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <form class="mb-2" action="" method="POST">
                    <div class="input-group">
                        <input type="text" class="form-control" name="keyword" placeholder="Cari buku..." autocomplete="off">
                        <button class="btn btn-primary" type="submit" name="tombol_search">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>   
            
            <table class="table table-striped table-hover">
                <tr>
                    <th>No.</th>
                    <th>Id Buku</th>
                    <th>Id Kategori</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Tanggal Input</th>
                    <th>Penulis</th>
                    <th>Judul</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
                <?php $no = $awalData + 1; ?>
                <?php foreach($buku as $data): ?>
                <tr>
                    <td> <?= $no ?> </td>
                    <td> <?= $data['id_buku'] ?> </td>
                    <td> <?= $data['id_kategori'] ?> </td>
                    <td> <?= $data['penerbit'] ?> </td>
                    <td> <?= $data['tahun'] ?> </td>
                    <td> <?= $data['tanggal_input'] ?> </td>
                    <td> <?= $data['penulis'] ?> </td>
                    <td> <?= $data['judul'] ?> </td>
                    <td> 
                        <img src="img/<?= $data['gambar'] ?> " height="70" width="70" alt="">
                    </td>
                    <td>
                        <a href="ubah_buku.php?id_buku=<?= $data['id_buku'] ?>">
                            <button class="btn-sm btn-success">Edit</button> </a>                        
                        <a href="hapus_buku.php?id_buku=<?= $data['id_buku'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?');">
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