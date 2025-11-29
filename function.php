<?php 
// Koneksi ke database simbs
// Variabel $conn akan digunakan di semua fungsi yang berinteraksi dengan DB
$conn = mysqli_connect("localhost", "root", "", "simbs");


// Fungsi untuk menampilkan data dari database
function query($query){
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ){
        $rows[] = $row;
    }
    return $rows;
}


// ----------------------------------------------------
// FUNGSI CRUD UNTUK TABEL KATEGORI
// ----------------------------------------------------
function tambah_kategori($data){
    global $conn;
    $nama_kategori = htmlspecialchars($data['nama_kategori']);
    // tanggal_input akan otomatis terisi oleh kolom TIMESTAMP
    $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);    
}

function hapus_kategori($id){
    global $conn;
    // Perlu menghapus semua buku yang berelasi dengan kategori ini (jika ada) 
    // atau batalkan jika ada batasan (misal: foreign key constraint).
    $query = "DELETE FROM kategori WHERE id_kategori = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);    
}

function ubah_kategori($data){
    global $conn;
    $id_kategori = $data['id_kategori'];
    $nama_kategori = htmlspecialchars($data['nama_kategori']);
    // tanggal_input akan otomatis terupdate oleh ON UPDATE CURRENT_TIMESTAMP(6)
    $query = "UPDATE kategori SET
                nama_kategori = '$nama_kategori'
              WHERE id_kategori = $id_kategori
              ";
     $result = mysqli_query($conn, $query);
     return mysqli_affected_rows($conn); 
}

function search_kategori($keyword){
    global $conn;
    // Mencari data kategori sesuai kata kunci
    $query = "SELECT * FROM kategori
              WHERE
              nama_kategori LIKE '%$keyword%'
              ORDER BY tanggal_input DESC
            ";
    return query($query);
}

// =========================================================================
// PERBAIKAN FUNGSI UPLOAD GAMBAR (img)
// =========================================================================
// Fungsi untuk mengurus proses upload gambar untuk data Buku
// Mengembalikan nama file yang unik (string) jika sukses, atau false (boolean) jika gagal validasi.
function img() {
    
    // setting gambar
    $namaFile = $_FILES['img']['name'];
    $ukuranFile = $_FILES['img']['size'];
    $error = $_FILES['img']['error'];
    $tmpName = $_FILES['img']['tmp_name'];

    // 1. Cek apakah tidak ada gambar yang diupload (kode error 4 = NO FILE UPLOADED)
    if( $error === 4 ) {
        // Jika tidak ada file diupload, kembalikan nama file default.
        return 'default.png'; // Gunakan default.png atau nama gambar placeholder Anda
    }

    // 2. Cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
        echo "<script>
                alert('Yang Anda upload bukan gambar! Hanya izinkan JPG/JPEG/PNG.');
              </script>";
        return false; // *PENTING: kembalikan FALSE jika gagal validasi*
    }

    // 3. Cek jika ukurannya terlalu besar (maks 5MB)
    // 5000000 bytes = 5 Megabyte
    if( $ukuranFile > 5000000 ) {
        echo "<script>
                alert('Ukuran gambar terlalu besar! Maksimal 5MB.');
              </script>";
        return false; // *PENTING: kembalikan FALSE jika gagal validasi*
    }

    // 4. Lolos pengecekan, gambar siap diupload
    // generate nama gambar baru yang unik (untuk mencegah file tertimpa)
    $namaFileBaru = uniqid(); 
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar; 

    // Pindahkan file ke folder 'img/buku/' (Pastikan folder ini ada!)
    // *PERHATIKAN PATH INI: 'img/' diubah menjadi 'img/buku/'*
    if (!move_uploaded_file($tmpName, 'img/' . $namaFileBaru)) {
        // Jika gagal move, biasanya karena permission folder.
        echo "<script>
                alert('Gagal memindahkan file. Cek izin folder img/ di server Anda!');
              </script>";
        return false;
    }

    // Kembalikan nama file yang unik untuk disimpan di database
    return $namaFileBaru;
}
// =========================================================================
// PERBAIKAN FUNGSI TAMBAH BUKU
// =========================================================================
function tambah_buku($data){
    global $conn;
    
    // 1. UPLOAD GAMBAR DULU
    $gambar = img(); 
    
    // 2. CEK JIKA UPLOAD GAGAL (img() mengembalikan boolean false)
    if( $gambar === false ) {
        return 0; // Menghentikan proses dan mengembalikan 0 (gagal)
    }

    // 3. Jika upload/validasi berhasil atau menggunakan default.png, lanjutkan
    $id_kategori = htmlspecialchars($data['id_kategori']);
    $penerbit = htmlspecialchars($data['penerbit']);
    $tahun = htmlspecialchars($data['tahun']);
    $penulis = htmlspecialchars($data['penulis']);
    $judul = htmlspecialchars($data['judul']);
    // $data['gambar'] SUDAH TIDAK DIGUNAKAN, diganti dengan variabel $gambar

    // tanggal_input akan otomatis terisi oleh kolom TIMESTAMP
    $query = "INSERT INTO buku (id_kategori, penerbit, tahun, penulis, judul, gambar)
              VALUES ('$id_kategori', '$penerbit', '$tahun', '$penulis', '$judul', '$gambar')
              ";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);    
}

// =========================================================================
// PERBAIKAN FUNGSI UBAH BUKU
// =========================================================================
function ubah_buku($data){
    global $conn;
    
    $id_buku = $data['id_buku'];
    // *PENTING*: Ambil nama file gambar lama dari input hidden di form
    $gambarLama = $data['gambarLama']; 
    
    // Cek apakah user memilih gambar baru (error 4 artinya tidak ada file baru dipilih)
    if( $_FILES['img']['error'] === 4 ) {
        $gambar = $gambarLama; // Gunakan gambar lama jika tidak ada upload baru
    } else {
        // Ada gambar baru yang diupload, panggil fungsi img()
        $gambar = img();

        // Cek jika proses upload gambar baru gagal
        if( $gambar === false ) {
            return 0; // Menghentikan proses update
        }
        
        // Hapus gambar lama (hanya jika gambar lama bukan default)
        if ($gambarLama !== 'default.png' && file_exists('img/' . $gambarLama)) {
            unlink('img/' . $gambarLama);
        }
    }


    function search_buku($keyword){
    global $conn;
    // Mencari data buku sesuai kata kunci
    $query = "SELECT * FROM buku
              WHERE
              judul LIKE '%$keyword%'
              ORDER BY tanggal_input DESC
            ";
    return query($query);
}


    // Lanjutkan dengan data form lainnya
    $id_kategori = htmlspecialchars($data['id_kategori']);
    $penerbit = htmlspecialchars($data['penerbit']);
    $tahun = htmlspecialchars($data['tahun']);
    $penulis = htmlspecialchars($data['penulis']);
    $judul = htmlspecialchars($data['judul']);
    // $data['gambar'] SUDAH TIDAK DIGUNAKAN, diganti dengan variabel $gambar
    
    // tanggal_input akan otomatis terupdate oleh ON UPDATE CURRENT_TIMESTAMP(6)
    $query = "UPDATE buku SET
                id_kategori = '$id_kategori',
                penerbit = '$penerbit',
                tahun = '$tahun',
                penulis = '$penulis',
                judul = '$judul',
                gambar = '$gambar'
              WHERE id_buku = $id_buku
              ";
     $result = mysqli_query($conn, $query);
     return mysqli_affected_rows($conn); 
}

//----------------------------------------------------
// FUNGSI AUTHENTIKASI
//------------------------------------------------------
function register($data_register){
    global $conn;
    $username = strtolower(stripslashes($data_register['username'])); // strtolower dan stripslashes
    $email = $data_register['email'];
    $password = mysqli_real_escape_string($conn, $data_register['password']);
    $confirm_password = mysqli_real_escape_string($conn, $data_register['confirm_password']);

    // 1. Cek apakah username atau email sudah terdaftar
    $query_check = mysqli_query($conn, "SELECT username, email FROM user WHERE username = '$username' OR email = '$email'");
    if(mysqli_num_rows($query_check) > 0){
        return "username atau email sudah terdaftar, gunakan yang lain";
    }

    // 2. Cek panjang password (minimal 8 karakter)
    if(strlen($data_register['password']) < 8){
        return "password harus mengandung minimal 8 karakter";
    }

    // 3. Cek konfirmasi password
    if($password !== $confirm_password){
        return "Konfirmasi password tidak sesuai!";
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user baru ke database
    mysqli_query($conn, "INSERT INTO user (username, email, password) VALUES('$username', '$email', '$password')");
    
    // Cek apakah ada baris yang terpengaruh
    if (mysqli_affected_rows($conn) > 0) {
        return true;
    } else {
        return "Gagal mendaftar. Silakan coba lagi.";
    }
}

function login($data) {
    global $conn;
    $username = $data['username'];
    $password = $data['password'];

    // Cek user ada atau tidak berdasarkan username
    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
     // Cek user ada atau tidak
    if(mysqli_num_rows($result) === 1){
        $row = mysqli_fetch_assoc($result);
        // Verify password
        if(password_verify($password, $row["password"])) {
            // Login berhasil 
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username']; // Tampilkan username yang "sedang login" di navbar 
            return true;
        } else {
            // Password salah 
            return "salah password";
        }
    } else {
        // Username tidak ditemukan 
        return "username salah";
    }
}

//----------------------------------------------------
// FUNGSI SEARCH BUKU (Optional, tapi diperlukan jika tombol search diaktifkan)
//------------------------------------------------------
function search_buku($keyword){
    global $conn;
    $query = "SELECT buku.*, kategori.nama_kategori 
              FROM buku
              LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori
              WHERE
              judul LIKE '%$keyword%' OR
              penulis LIKE '%$keyword%' OR
              penerbit LIKE '%$keyword%' OR
              nama_kategori LIKE '%$keyword%'
              ORDER BY tanggal_input DESC
            ";
    return query($query);
}

//----------------------------------------------------
// FUNGSI PAGINASI BUKU (Tidak perlu diubah)
//------------------------------------------------------
function get_buku_paginated($awalData, $jumlahDataPerHalaman, $keyword = null) {
    // Fungsi ini akan mengambil data buku dengan JOIN kategori, sudah diatur di index.php
    // Anda bisa memindahkan logika di index.php ke sini jika mau, tapi saat ini sudah benar di index.php
}
?>