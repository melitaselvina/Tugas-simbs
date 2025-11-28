<?php 
    session_start();
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }
    require("function.php");
    
    // Ambil id dari URL
    $id_buku = $_GET['id_buku'];
    
    // Panggil fungsi hapus_buku()
    if(hapus_buku($id_buku) > 0){
        echo "
            <script>
                alert('Data buku berhasil dihapus!');
                document.location.href = 'index.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Data buku gagal dihapus!');
                document.location.href = 'index.php';
            </script>
        ";
    }
?>