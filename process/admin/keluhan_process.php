<?php
session_start();
require_once '../../config/database.php';
global $conn;
// Validasi Keamanan: Pastikan hanya admin terotentikasi yang bisa memproses
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    die("Akses ditolak secara ilegal.");
}

if (isset($_POST['update_keluhan'])) {
    
    $id_keluhan    = mysqli_real_escape_string($conn, $_POST['id_keluhan']);
    $status        = mysqli_real_escape_string($conn, $_POST['status']);
    $catatan_admin = trim($_POST['catatan_admin']);

    // Amankan string input teks tanggapan
    $catatan_admin = mysqli_real_escape_string($conn, $catatan_admin);

    // Jalankan query update status dan catatan pengurus RT
    $query = "UPDATE keluhan SET 
              status = '$status', 
              catatan_admin = '$catatan_admin' 
              WHERE id_keluhan = '$id_keluhan'";

    if (mysqli_query($conn, $query)) {
        // Jika sukses, kembalikan admin ke dashboard kelola keluhan
        header("Location: ../../pages/admin/keluhan.php?pesan=sukses_tanggapi");
        exit;
    } else {
        die("Gagal memperbarui keluhan warga: " . mysqli_error($conn));
    }

} else {
    header("Location: ../../pages/admin/keluhan.php");
    exit;
}
?>
