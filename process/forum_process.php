<?php
session_start();
require_once '../config/database.php';
global $conn;
// 1. Pastikan user sudah login, karena kita butuh id_user untuk mencatat siapa pembuatnya
if (!isset($_SESSION['id_user'])) {
    die("Akses ditolak. Anda harus login terlebih dahulu untuk membuat diskusi.");
}

// 2. Cek apakah form postingan dikirim
if (isset($_POST['kirim_postingan'])) {
    
    $id_user          = $_SESSION['id_user']; // Diambil dari session login aktif
    $judul_postingan  = trim($_POST['judul_postingan']);
    $isi_postingan    = trim($_POST['isi_postingan']);

    // 3. Validasi input kosong
    if (empty($judul_postingan) || empty($isi_postingan)) {
        die("Judul dan isi diskusi tidak boleh kosong.");
    }

    // 4. Amankan string dari karakter aneh / tanda petik (Cara Biasa)
    $judul_postingan  = mysqli_real_escape_string($conn, $judul_postingan);
    $isi_postingan    = mysqli_real_escape_string($conn, $isi_postingan);

    // 5. Query Insert data sesuai nama kolom tabel kamu
    // Menggunakan NOW() untuk mengisi tanggal secara real-time berdasarkan jam server
    $query = "INSERT INTO postingan (id_user, judul_postingan, isi_postingan, tanggal_posting, updated_at) 
              VALUES ('$id_user', '$judul_postingan', '$isi_postingan', NOW(), NOW())";

    if (mysqli_query($conn, $query)) {
        
        // Jika berhasil, kembalikan warga ke halaman forum dengan parameter sukses
        header("Location: ../pages/forum/index.php?pesan=sukses_posting");
        exit;

    } else {
        die("Gagal membuat diskusi baru: " . mysqli_error($conn));
    }

} else {
    // Jika coba-coba akses langsung tanpa pencet tombol form
    header("Location: ../pages/forum/index.php");
    exit;
}
?>