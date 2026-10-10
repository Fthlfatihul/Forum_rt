<?php
session_start();
require_once '../../config/database.php';

// Validasi Keamanan: Pastikan hanya admin yang bisa mengeksekusi
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    die("Akses ilegal ditolak.");
}
global $conn;
// Pastikan ada parameter 'action' dan 'id' di URL
if (isset($_GET['action']) && isset($_GET['id'])) {
    
    $action = $_GET['action'];
    $id     = mysqli_real_escape_string($conn, $_GET['id']);

    // LOGIKA 1: JIKA ADMIN MENGHAPUS SATU TOPIK POSTINGAN UTUH
    if ($action === 'delete_post') {
        
        // Sesuai relasi data, hapus dulu semua komentar di dalam postingan tersebut
        $query_hapus_komen = "DELETE FROM komentar WHERE id_postingan = '$id'";
        mysqli_query($conn, $query_hapus_komen);
        
        // Baru kemudian hapus data postingan utamanya
        $query_hapus_post  = "DELETE FROM postingan WHERE id_postingan = '$id'";
        
        if (mysqli_query($conn, $query_hapus_post)) {
            header("Location: ../../pages/admin/forum.php?pesan=sukses_hapus_topik");
            exit;
        } else {
            die("Gagal menghapus topik diskusi: " . mysqli_error($conn));
        }
    } 
    
    // LOGIKA 2: JIKA ADMIN HANYA MENGHAPUS SATU BARIS KOMENTAR YANG MELANGGAR
    else if ($action === 'delete_comment') {
        
        $query_hapus_tunggal_komen = "DELETE FROM komentar WHERE id_komentar = '$id'";
        
        if (mysqli_query($conn, $query_hapus_tunggal_komen)) {
            header("Location: ../../pages/admin/forum.php?pesan=sukses_hapus_komentar");
            exit;
        } else {
            die("Gagal menghapus komentar: " . mysqli_error($conn));
        }
    } 
    
    // Jika parameter action tidak dikenal
    else {
        header("Location: ../../pages/admin/forum.php");
        exit;
    }

} else {
    header("Location: ../../pages/admin/forum.php");
    exit;
}
?>