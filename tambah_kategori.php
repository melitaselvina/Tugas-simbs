<?php 
    session_start();
    // Cek apakah user sudah login, jika belum redirect ke login.php
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }
    
    // Panggil file function.php yang berisi koneksi DB dan fungsi CRUD
    require("function.php");
    
    // Cek apakah tombol submit sudah ditekan
    if(isset($_POST['tombol_submit'])){
        // Panggil fungsi tambah_kategori()
        if(tambah_kategori($_POST) > 0){
            echo "
                <script>
                    alert('Data kategori berhasil ditambahkan ke database!');
                    document.location.href = 'kategori.php';
                </script>
            ";
        }else{
            echo "
                <script>
                    alert('Data kategori gagal ditambahkan ke database!');
                    document.location.href = 'kategori.php';
                </script>
            ";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kategori - SIMBS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="p-4 container">
        <div class="row">
            <h1 class="mb-2">Tambah Data Kategori</h1>
            <a href="kategori.php" class="mb-2">Kembali ke Data Kategori</a>
            
            <div class="col-md-6">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label fw-bold">Nama Kategori</label>
                        <input type="text" class="form-control" name="nama_kategori" id="nama_kategori" 
                               placeholder="Masukkan nama kategori baru" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="tombol_submit" class="btn-sm btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>