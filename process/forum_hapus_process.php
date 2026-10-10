<?php
session_start();
require_once '../config/database.php';
global $conn;
if (!isset($_SESSION['id_user']) || !isset($_GET['id'])) {
    header("Location: ../pages/forum/index.php");
    exit;
}

$id_user_login = $_SESSION['id_user'];
$id_postingan  = mysqli_real_escape_string($conn, $_GET['id']);

// KEAMANAN: Cek kepemilikan diskusi sebelum menghapus
$cek_pemilik = "SELECT id_user FROM postingan WHERE id_postingan = '$id_postingan'";
$hasil_cek   = mysqli_query($conn, $cek_pemilik);

if (mysqli_num_rows($hasil_cek) > 0) {
    $data_post = mysqli_fetch_assoc($hasil_cek);
    
    if ($data_post['id_user'] == $id_user_login) {
        
        // 1. Hapus terlebih dahulu semua komentar yang terikat pada postingan ini
        $query_hapus_komentar = "DELETE FROM komentar WHERE id_postingan = '$id_postingan'";
        mysqli_query($conn, $query_hapus_komentar);
        
        // 2. Baru hapus data postingan utamanya
        $query_hapus_postingan = "DELETE FROM postingan WHERE id_postingan = '$id_postingan'";
        
        if (mysqli_query($conn, $query_hapus_postingan)) {
            header("Location: ../pages/forum/index.php?pesan=sukses_hapus_forum");
            exit;
        } else {
            die("Gagal menghapus diskusi: " . mysqli_error($conn));
        }
    } else {
        die("Anda tidak diizinkan menghapus diskusi milik warga lain.");
    }
} else {
    die("Topik diskusi tidak ditemukan.");
}
?>