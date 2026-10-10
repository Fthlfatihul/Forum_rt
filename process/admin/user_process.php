<?php
session_start();
require_once '../../config/database.php';
global $conn;
// Validasi Keamanan: Pastikan hanya admin yang bisa mengeksekusi
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    die("Akses ditolak secara ilegal.");
}

// Pastikan ada parameter 'action' dan 'id' pada URL
if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] === 'delete') {
    
    $id_user_hapus = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. KEAMANAN TAMBAHAN: Cegah sistem menghapus akun admin itu sendiri lewat rekayasa URL
    $cek_peran = mysqli_query($conn, "SELECT peran FROM users WHERE id_user = '$id_user_hapus'");
    if (mysqli_num_rows($cek_peran) > 0) {
        $data = mysqli_fetch_assoc($cek_peran);
        
        if ($data['peran'] === 'admin') {
            die("Kesalahan Keamanan: Anda tidak diizinkan menghapus akun sesama Admin.");
        }
    } else {
        die("Akun warga tidak ditemukan.");
    }

    /* 
       2. OPSIONAL STRATEGI DATABASE:
       Jika tabel keluhan, postingan, atau komentar menggunakan Foreign Key yang mengikat 'id_user',
       sebaiknya hapus dulu data anak/anak relasinya agar tidak terjadi error CONSTRAINT.
    */
    mysqli_query($conn, "DELETE FROM komentar WHERE id_user = '$id_user_hapus'");
    mysqli_query($conn, "DELETE FROM postingan WHERE id_user = '$id_user_hapus'");
    mysqli_query($conn, "DELETE FROM keluhan WHERE id_user = '$id_user_hapus'");

    // 3. Eksekusi Hapus data utama di tabel users
    $query_hapus_user = "DELETE FROM users WHERE id_user = '$id_user_hapus'";

    if (mysqli_query($conn, $query_hapus_user)) {
        // Jika sukses, kembalikan admin ke halaman kelola warga
        header("Location: ../../pages/admin/user.php?pesan=sukses_hapus_warga");
        exit;
    } else {
        die("Gagal menghapus data warga: " . mysqli_error($conn));
    }

} else {
    header("Location: ../../pages/admin/user.php");
    exit;
}
?>