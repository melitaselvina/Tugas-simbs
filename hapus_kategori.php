<?php 
    session_start();
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }
    require("function.php");
    
    // Ambil id dari URL
    $id_kategori = $_GET['id_kategori'];
    
    // Panggil fungsi hapus_kategori()
    if(hapus_kategori($id_kategori) > 0){
        echo "
            <script>
                alert('Data kategori berhasil dihapus!');
                document.location.href = 'kategori.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Data kategori gagal dihapus atau data terkait buku masih ada!');
                document.location.href = 'kategori.php';
            </script>
        ";
    }
?>