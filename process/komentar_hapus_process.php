<?php
session_start();
require_once '../config/database.php';
global $conn;
if (!isset($_SESSION['id_user']) || !isset($_GET['id'])) {
    header("Location: ../pages/forum/index.php");
    exit;
}

$id_user_login = $_SESSION['id_user'];
$id_komentar   = mysqli_real_escape_string($conn, $_GET['id']);

// KEAMANAN: Cek kepemilikan data sebelum melakukan query DELETE
$cek_pemilik = "SELECT id_user FROM komentar WHERE id_komentar = '$id_komentar'";
$hasil_cek   = mysqli_query($conn, $cek_pemilik);

if (mysqli_num_rows($hasil_cek) > 0) {
    $data_komen = mysqli_fetch_assoc($hasil_cek);
    
    if ($data_komen['id_user'] == $id_user_login) {
        
        // Eksekusi Hapus data dari tabel komentar
        $query_hapus = "DELETE FROM komentar WHERE id_komentar = '$id_komentar'";
        
        if (mysqli_query($conn, $query_hapus)) {
            header("Location: ../pages/forum/index.php?pesan=sukses_hapus_komentar");
            exit;
        } else {
            die("Gagal menghapus komentar: " . mysqli_error($conn));
        }
    } else {
        die("Anda tidak diizinkan menghapus komentar orang lain.");
    }
} else {
    die("Komentar tidak ditemukan.");
}
?>