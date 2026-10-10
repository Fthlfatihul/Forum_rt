<?php
session_start();
require_once '../config/database.php';
global $conn;
// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    die("Akses ditolak. Anda harus login terlebih dahulu.");
}

// Cek apakah tombol kirim keluhan ditekan
if (isset($_POST['kirim_keluhan'])) {

    // 1. Ambil data dari form
    $id_user        = $_SESSION['id_user']; 
    $judul_keluhan  = trim($_POST['judul_keluhan']);
    $lokasi         = trim($_POST['lokasi']);
    $deskripsi      = trim($_POST['deskripsi']);
    
    // Status awal keluhan
    $status         = 'Menunggu'; 
    $catatan_admin  = ''; 

    // 2. Validasi input kosong
    if (empty($judul_keluhan) || empty($lokasi) || empty($deskripsi)) {
        die("Semua kolom formulir wajib diisi.");
    }

    // 3. Amankan string (Cara Biasa)
    $judul_keluhan  = mysqli_real_escape_string($conn, $judul_keluhan);
    $lokasi         = mysqli_real_escape_string($conn, $lokasi);
    $deskripsi      = mysqli_real_escape_string($conn, $deskripsi);

    // 4. MEMBUAT KODE KELUHAN OTOMATIS (Format: KL-001)
    // Ambil kode keluhan terakhir dari database
    $query_kode = "SELECT kode_keluhan FROM keluhan ORDER BY id_keluhan DESC LIMIT 1";
    $eksekusi_kode = mysqli_query($conn, $query_kode);
    
    if (mysqli_num_rows($eksekusi_kode) > 0) {
        $data_kode = mysqli_fetch_assoc($eksekusi_kode);
        
        // Mengambil angka dari kode terakhir (misal 'KL-001' diambil '001')
        $angka_terakhir = (int) substr($data_kode['kode_keluhan'], 3); 
        
        // Tambah 1 dan beri padding agar tetap 3 digit (misal: 2 menjadi '002')
        $angka_baru = str_pad($angka_terakhir + 1, 3, '0', STR_PAD_LEFT);
    } else {
        // Jika belum ada keluhan sama sekali di database, mulai dari 001
        $angka_baru = '001';
    }
    
    $kode_keluhan = "KL-" . $angka_baru; // Hasilnya: KL-001, KL-002, dst.


    // 5. QUERY SIMPAN KE DATABASE
    // *Catatan: Pastikan nama tabel 'user_keluhan' sama dengan yang ada di databasemu
    $query = "INSERT INTO keluhan (kode_keluhan, id_user, judul_keluhan, deskripsi, lokasi, status, catatan_admin, tanggal_keluhan, updated_at)
              VALUES ('$kode_keluhan', '$id_user', '$judul_keluhan', '$deskripsi', '$lokasi', '$status', '$catatan_admin', NOW(), NOW())";

    if (mysqli_query($conn, $query)) {
        
        // Jika sukses, kembalikan ke halaman keluhan
        header("Location: ../pages/keluhan/index.php?pesan=sukses");
        exit;

    } else {
        die("Gagal mengirim keluhan: " . mysqli_error($conn));
    }

} else {
    header("Location: ../pages/keluhan/index.php");
    exit;
}
?>