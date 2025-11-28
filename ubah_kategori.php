<?php 
    session_start();
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }
    require("function.php");
    
    // Ambil id dari URL
    $id_kategori = $_GET['id_kategori'];
    // Ambil data kategori berdasarkan id (menggunakan query()[0] karena hanya 1 data)
    $kategori = query("SELECT * FROM kategori WHERE id_kategori = $id_kategori")[0];
    
    if(isset($_POST['tombol_submit'])){
        if(ubah_kategori($_POST) > 0){
            echo "
                <script>
                    alert('Data kategori berhasil diubah di database!');
                    document.location.href = 'kategori.php';
                </script>
            ";
        }else{
            echo "
                <script>
                    alert('Data kategori gagal diubah di database!');
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
    <title>Ubah Data Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="p-4 container">
        <div class="row">
            <h1 class="mb-2">Ubah Data Kategori</h1>
            <a href="kategori.php" class="mb-2">Kembali</a>
            <div class="col-md-6">
                <form action="" method="POST">
                    <input type="hidden" name="id_kategori" value="<?= $kategori['id_kategori'] ?>">      
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kategori</label>
                        <input type="text" class="form-control" name="nama_kategori" id="nama_kategori" 
                               value="<?= $kategori['nama_kategori'] ?>" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="tombol_submit" class="btn-sm btn-primary">Submit Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>