<?php
session_start();
require_once '../config/database.php';
global $conn;
// 1. Pastikan user sudah login sebelum bisa berkomentar
if (!isset($_SESSION['id_user'])) {
    die("Akses ditolak. Anda harus login terlebih dahulu untuk mengirim komentar.");
}

// 2. Cek apakah tombol kirim komentar ditekan
if (isset($_POST['kirim_komentar'])) {

    $id_user        = $_SESSION['id_user']; // ID user diambil dari session login aktif
    $id_postingan   = mysqli_real_escape_string($conn, $_POST['id_postingan']); // ID postingan tempat berkomentar
    $isi_komentar   = trim($_POST['isi_komentar']);

    // 3. Validasi input agar tidak mengirim komentar kosong
    if (empty($isi_komentar)) {
        die("Isi komentar tidak boleh kosong.");
    }

    // 4. Amankan teks komentar (Cara Biasa)
    $isi_komentar   = mysqli_real_escape_string($conn, $isi_komentar);

    // 5. Query Insert data ke tabel komentar
    // Menggunakan NOW() untuk mencatat waktu komentar dikirim secara otomatis
    $query = "INSERT INTO komentar (id_postingan, id_user, isi_komentar, tanggal_komentar) 
              VALUES ('$id_postingan', '$id_user', '$isi_komentar', NOW())";

    if (mysqli_query($conn, $query)) {
        
        // 6. UPDATE juga kolom `updated_at` di tabel postingan 
        // Agar postingan yang baru saja dikomentari otomatis naik ke urutan paling atas di feed forum
        $query_update_post = "UPDATE postingan SET updated_at = NOW() WHERE id_postingan = '$id_postingan'";
        mysqli_query($conn, $query_update_post);

        // Jika sukses, kembalikan user ke halaman forum
        header("Location: ../pages/forum/index.php?pesan=sukses_komentar");
        exit;

    } else {
        die("Gagal mengirim komentar: " . mysqli_error($conn));
    }

} else {
    // Jika diakses langsung tanpa submit form, kembalikan ke forum
    header("Location: ../pages/forum/index.php");
    exit;
}
?>