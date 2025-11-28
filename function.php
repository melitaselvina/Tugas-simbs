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
    // Untuk tujuan uji kompetensi ini, kita asumsikan hapus langsung, 
    // tetapi di sistem nyata ini berbahaya.
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
			"; // Data buku ditampilkan berdasarkan tanggal_input terbaru [cite: 19, 20]
	return query($query);
}

// ----------------------------------------------------
// FUNGSI CRUD UNTUK TABEL BUKU
// ----------------------------------------------------
function tambah_buku($data){
    global $conn;
    $id_kategori = htmlspecialchars($data['id_kategori']);
    $penerbit = htmlspecialchars($data['penerbit']);
    $tahun = htmlspecialchars($data['tahun']);
    $penulis = htmlspecialchars($data['penulis']);
    $judul = htmlspecialchars($data['judul']);
    // tanggal_input akan otomatis terisi oleh kolom TIMESTAMP
    $query = "INSERT INTO buku (id_kategori, penerbit, tahun, penulis, judul)
              VALUES ('$id_kategori', '$penerbit', '$tahun', '$penulis', '$judul')
             ";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);    
}

function hapus_buku($id){
    global $conn;
    $query = "DELETE FROM buku WHERE id_buku = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);    
}

function ubah_buku($data){
    global $conn;
    $id_buku = $data['id_buku'];
    $id_kategori = htmlspecialchars($data['id_kategori']);
    $penerbit = htmlspecialchars($data['penerbit']);
    $tahun = htmlspecialchars($data['tahun']);
    $penulis = htmlspecialchars($data['penulis']);
    $judul = htmlspecialchars($data['judul']);
    // tanggal_input akan otomatis terupdate oleh ON UPDATE CURRENT_TIMESTAMP(6)
    $query = "UPDATE buku SET
                id_kategori = '$id_kategori',
                penerbit = '$penerbit',
                tahun = '$tahun',
                penulis = '$penulis',
                judul = '$judul'
              WHERE id_buku = $id_buku
             ";
     $result = mysqli_query($conn, $query);
     return mysqli_affected_rows($conn); 
}

// ----------------------------------------------------
// FUNGSI AUTHENTIKASI
// ----------------------------------------------------
function register($data_register){
    global $conn;
    $username = strtolower(stripslashes($data_register['username'])); // strtolower dan stripslashes
    $email = $data_register['email'];
    $password = mysqli_real_escape_string($conn, $data_register['password']);
    $confirm_password = mysqli_real_escape_string($conn, $data_register['confirm_password']);

    // 1. Cek apakah username atau email sudah terdaftar [cite: 41]
    $query_check = mysqli_query($conn, "SELECT username, email FROM user WHERE username = '$username' OR email = '$email'");
    if(mysqli_num_rows($query_check) > 0){
        return "username atau email sudah terdaftar, gunakan yang lain"; // [cite: 41]
    }

    // 2. Cek panjang password (minimal 8 karakter) [cite: 42]
    if(strlen($data_register['password']) < 8){
        return "password harus mengandung minimal 8 karakter"; // [cite: 42]
    }

    // 3. Cek konfirmasi password
    if($password !== $confirm_password){
        return "Konfirmasi password tidak sesuai!";
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user baru ke database
    // id_user adalah AUTO_INCREMENT
    mysqli_query($conn, "INSERT INTO user (username, email, password) VALUES('$username', '$email', '$password')");
    
    // Cek apakah ada baris yang terpengaruh
    if (mysqli_affected_rows($conn) > 0) {
        return true;
    } else {
        return "Gagal mendaftar. Silakan coba lagi.";
    }
}

function login($data){
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
            // Login berhasil [cite: 34]
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username']; // Tampilkan username yang "sedang login" di navbar [cite: 34]
            return true;
        } else {
            // Password salah [cite: 33]
            return "salah password";
        }
    } else {
        // Username tidak ditemukan [cite: 33]
        return "username salah";
    }
}
// Fungsi upload_gambar() dihilangkan karena tidak ada di spesifikasi SIMBS, tetapi dipertahankan di file mahasiswa sebelumnya
function upload_gambar($nim, $nama) {
    // Fungsi ini tidak relevan untuk SIMBS, tetapi dipertahankan dari kode asli jika diperlukan
    // ... (Logika fungsi upload_gambar)
    // ...
    // ...
    return false; // Mengembalikan false agar tidak mengganggu jika dipanggil
}
?>