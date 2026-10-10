<?php
session_start();
require_once '../config/database.php';
global $conn;
// Validasi login
if (!isset($_SESSION['id_user'])) {
    die("Akses ditolak.");
}

if (isset($_POST['update_keluhan'])) {
    $id_user_login = $_SESSION['id_user'];
    $id_keluhan    = mysqli_real_escape_string($conn, $_POST['id_keluhan']);
    
    $judul_keluhan  = trim($_POST['judul_keluhan']);
    $lokasi         = trim($_POST['lokasi']);
    $deskripsi      = trim($_POST['deskripsi']);

    if (empty($judul_keluhan) || empty($lokasi) || empty($deskripsi)) {
        die("Semua kolom formulir wajib diisi.");
    }

    $judul_keluhan  = mysqli_real_escape_string($conn, $judul_keluhan);
    $lokasi         = mysqli_real_escape_string($conn, $lokasi);
    $deskripsi      = mysqli_real_escape_string($conn, $deskripsi);

    // KUNCI KEAMANAN ABSOLUT: Cek status terakhir di database sebelum update
    $cek_status_query = "SELECT status FROM keluhan WHERE id_keluhan = '$id_keluhan' AND id_user = '$id_user_login'";
    $eksekusi_cek = mysqli_query($conn, $cek_status_query);
    
    if (mysqli_num_rows($eksekusi_cek) == 0) {
        die("Keluhan tidak ditemukan.");
    }

    $data_status = mysqli_fetch_assoc($eksekusi_cek);

    // Jika di database statusnya ternyata sudah berubah (bukan Menunggu lagi), gagalkan!
    if ($data_status['status'] !== 'Menunggu') {
        die("Gagal mengedit. Status keluhan sudah berubah dan tidak bisa diubah kembali.");
    }

    // Eksekusi UPDATE data dan perbarui kolom updated_at
    $query_update = "UPDATE keluhan SET 
                     judul_keluhan = '$judul_keluhan', 
                     lokasi = '$lokasi', 
                     deskripsi = '$deskripsi', 
                     updated_at = NOW() 
                     WHERE id_keluhan = '$id_keluhan' AND id_user = '$id_user_login'";

    if (mysqli_query($conn, $query_update)) {
        // Redirect kembali ke list keluhan dengan parameter sukses edit
        header("Location: ../pages/keluhan/index.php?pesan=sukses_edit");
        exit;
    } else {
        die("Gagal memperbarui data: " . mysqli_error($conn));
    }

} else {
    header("Location: ../pages/keluhan/index.php");
    exit;
}
?>