<?php 
    session_start();
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }
    require("function.php");
    
    // Ambil id dari URL
    $id_buku = $_GET['id_buku'];
    // Ambil data buku berdasarkan id
    $buku = query("SELECT * FROM buku WHERE id_buku = $id_buku")[0];

    // Ambil semua data kategori untuk dropdown
    $kategori = query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
    
    if(isset($_POST['tombol_submit'])){
        if(ubah_buku($_POST) > 0){
            echo "
                <script>
                    alert('Data buku berhasil diubah di database!');
                    document.location.href = 'index.php';
                </script>
            ";
        }else{
            echo "
                <script>
                    alert('Data buku gagal diubah di database!');
                    document.location.href = 'index.php';
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
    <title>Ubah Data Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="p-4 container">
        <div class="row">
            <h1 class="mb-2">Ubah Data Buku</h1>
            <a href="index.php" class="mb-2">Kembali</a>
            <div class="col-md-6">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_buku" value="<?= $buku['id_buku'] ?>">      
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul</label>
                        <input type="text" class="form-control" name="judul" id="judul" 
                               value="<?= $buku['judul'] ?>" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select class="form-select" name="id_kategori" id="id_kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach($kategori as $kat) : ?>
                                <option value="<?= $kat['id_kategori'] ?>" 
                                    <?= ($kat['id_kategori'] == $buku['id_kategori']) ? 'selected' : '' ?>>
                                    <?= $kat['nama_kategori'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Penulis</label>
                        <input type="text" class="form-control" name="penulis" id="penulis" 
                               value="<?= $buku['penulis'] ?>" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Penerbit</label>
                        <input type="text" class="form-control" name="penerbit" id="penerbit" 
                               value="<?= $buku['penerbit'] ?>" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tahun</label>
                        <input type="number" class="form-control" name="tahun" id="tahun" 
                               value="<?= $buku['tahun'] ?>" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label fw-bold">Gambar</label>
                        <input type="file" class="form-control" name="img" id="img">
                        <small class="form-text text-muted">Maksimal 2MB (JPG/JPEG/PNG)</small>
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