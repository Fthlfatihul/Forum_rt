<?php
session_start();
require_once '../config/database.php';
global $conn;
if (!isset($_SESSION['id_user'])) {
    die("Akses ditolak.");
}

if (isset($_POST['update_postingan'])) {
    $id_user_login   = $_SESSION['id_user'];
    $id_postingan    = mysqli_real_escape_string($conn, $_POST['id_postingan']);
    $judul_postingan = trim($_POST['judul_postingan']);
    $isi_postingan   = trim($_POST['isi_postingan']);

    if (empty($judul_postingan) || empty($isi_postingan)) {
        die("Judul dan isi postingan tidak boleh kosong.");
    }

    $judul_postingan = mysqli_real_escape_string($conn, $judul_postingan);
    $isi_postingan   = mysqli_real_escape_string($conn, $isi_postingan);

    // KEAMANAN: Pastikan postingan tersebut benar-benar milik user yang login
    $cek_pemilik = "SELECT id_user FROM postingan WHERE id_postingan = '$id_postingan'";
    $hasil_cek   = mysqli_query($conn, $cek_pemilik);
    $data_post   = mysqli_fetch_assoc($hasil_cek);

    if ($data_post['id_user'] != $id_user_login) {
        die("Anda tidak memiliki akses untuk mengedit diskusi ini.");
    }

    // Jalankan query update data diskusi
    $query_update = "UPDATE postingan SET 
                     judul_postingan = '$judul_postingan', 
                     isi_postingan = '$isi_postingan',
                     updated_at = NOW() 
                     WHERE id_postingan = '$id_postingan'";
    
    if (mysqli_query($conn, $query_update)) {
        header("Location: ../pages/forum/index.php?pesan=sukses_edit_forum");
        exit;
    } else {
        die("Gagal memperbarui diskusi: " . mysqli_error($conn));
    }
} else {
    header("Location: ../pages/forum/index.php");
    exit;
}
?>