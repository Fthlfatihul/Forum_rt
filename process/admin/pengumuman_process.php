<?php
// 1. Penanganan Session yang Lebih Aman
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Hubungkan ke Database
require_once '../../config/database.php';
global $conn;

// 3. Validasi Keamanan: Kunci ketat untuk admin saja
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    die("Akses ditolak secara ilegal.");
}

// Mengambil parameter action, default kosong jika tidak ada
$action = isset($_GET['action']) ? $_GET['action'] : '';

// ==================== AKSI 1: TAMBAH DATA (INSERT) ====================
if ($action === 'insert' && isset($_POST['submit_pengumuman'])) {
    
    $id_admin         = $_SESSION['id_user']; // Mengambil ID Admin dari session login
    $judul_pengumuman = mysqli_real_escape_string($conn, trim($_POST['judul_pengumuman']));
    $isi_pengumuman   = mysqli_real_escape_string($conn, trim($_POST['isi_pengumuman']));

    // Validasi input sederhana agar tidak memasukkan data kosong
    if (empty($judul_pengumuman) || empty($isi_pengumuman)) {
        die("Gagal: Judul dan isi pengumuman tidak boleh kosong!");
    }

    // Query INSERT disesuaikan dengan kolom tabel Anda
    $query = "INSERT INTO pengumuman (id_user, judul_pengumuman, isi_pengumuman, tanggal_pengumuman) 
              VALUES ('$id_admin', '$judul_pengumuman', '$isi_pengumuman', NOW())";

    if (mysqli_query($conn, $query)) {
        // PERHATIKAN: Pastikan susunan ../../ ini sesuai dengan letak file proses ini berada
        header("Location: ../../pages/admin/pengumuman.php?pesan=sukses_tambah");
        exit;
    } else {
        die("Gagal menambah pengumuman ke database: " . mysqli_error($conn));
    }
}

// ==================== AKSI 2: UBAH DATA (UPDATE) ====================
elseif ($action === 'update' && isset($_POST['update_pengumuman'])) {
    
    $id_pengumuman    = mysqli_real_escape_string($conn, $_POST['id_pengumuman']);
    $judul_pengumuman = mysqli_real_escape_string($conn, trim($_POST['judul_pengumuman']));
    $isi_pengumuman   = mysqli_real_escape_string($conn, trim($_POST['isi_pengumuman']));

    if (empty($judul_pengumuman) || empty($isi_pengumuman)) {
        die("Gagal: Judul dan isi pengumuman tidak boleh kosong!");
    }

    $query = "UPDATE pengumuman SET 
              judul_pengumuman = '$judul_pengumuman', 
              isi_pengumuman = '$isi_pengumuman' 
              WHERE id_pengumuman = '$id_pengumuman'";

    if (mysqli_query($conn, $query)) {
        header("Location: ../../pages/admin/pengumuman.php?pesan=sukses_update");
        exit;
    } else {
        die("Gagal memperbarui pengumuman: " . mysqli_error($conn));
    }
}

// ==================== AKSI 3: HAPUS DATA (DELETE) ====================
elseif ($action === 'delete' && isset($_GET['id'])) {
    
    $id_pengumuman = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "DELETE FROM pengumuman WHERE id_pengumuman = '$id_pengumuman'";

    if (mysqli_query($conn, $query)) {
        header("Location: ../../pages/admin/pengumuman.php?pesan=sukses_hapus");
        exit;
    } else {
        die("Gagal menghapus pengumuman: " . mysqli_error($conn));
    }
}

// Jika ada akses langsung tanpa action yang jelas, kembalikan ke halaman utama admin
else {
    header("Location: ../../pages/admin/pengumuman.php");
    exit;
}
?>