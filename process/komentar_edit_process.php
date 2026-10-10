<?php
session_start();
require_once '../config/database.php';
global $conn;

if (!isset($_SESSION['id_user'])) {
    die("Akses ditolak.");
}

if (isset($_POST['update_komentar'])) {
    $id_user_login = $_SESSION['id_user'];
    $id_komentar   = mysqli_real_escape_string($conn, $_POST['id_komentar']);
    $isi_komentar  = trim($_POST['isi_komentar']);

    if (empty($isi_komentar)) {
        die("Isi komentar tidak boleh kosong.");
    }

    $isi_komentar = mysqli_real_escape_string($conn, $isi_komentar);

    // KEAMANAN: Pastikan komentar tersebut benar-benar milik user yang sedang login
    $cek_pemilik = "SELECT id_user FROM komentar WHERE id_komentar = '$id_komentar'";
    $hasil_cek   = mysqli_query($conn, $cek_pemilik);
    $data_komen  = mysqli_fetch_assoc($hasil_cek);

    if ($data_komen['id_user'] != $id_user_login) {
        die("Anda tidak memiliki akses untuk mengedit komentar ini.");
    }

    // Eksekusi Update
    $query_update = "UPDATE komentar SET isi_komentar = '$isi_komentar' WHERE id_komentar = '$id_komentar'";
    
    if (mysqli_query($conn, $query_update)) {
        header("Location: ../pages/forum/index.php?pesan=sukses_edit_komentar");
        exit;
    } else {
        die("Gagal memperbarui komentar: " . mysqli_error($conn));
    }
} else {
    header("Location: ../pages/forum/index.php");
    exit;
}
?>