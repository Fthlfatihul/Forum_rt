<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// PROTEKSI KEAMANAN: Jika belum login ATAU bukan admin, tendang ke index luar
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../../index.php?pesan=akses_ditolak");
    exit;
}

require_once '../../config/database.php';
global $conn;

// --- QUERY HITUNG STATISTIK SECARA DINAMIS ---
// 1. Hitung keluhan warga yang berstatus 'Menunggu' atau 'Diproses'
$query_keluhan  = "SELECT COUNT(*) as total FROM keluhan WHERE status = 'Menunggu' OR status = 'Diproses'";
$exec_keluhan   = mysqli_query($conn, $query_keluhan);
$count_keluhan  = mysqli_fetch_assoc($exec_keluhan);

// 2. Hitung total topik diskusi di forum
$query_forum    = "SELECT COUNT(*) as total FROM postingan";
$exec_forum     = mysqli_query($conn, $query_forum);
$count_forum    = mysqli_fetch_assoc($exec_forum);

// 3. Hitung jumlah warga RT 05 yang sudah terdaftar di sistem
$query_warga    = "SELECT COUNT(*) as total FROM users WHERE peran = 'warga'";
$exec_warga     = mysqli_query($conn, $query_warga);
$count_warga    = mysqli_fetch_assoc($exec_warga);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Portal RT 05</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
    <link rel="stylesheet" href="../../assets/css/style-dashboard-admin.css">
</head>
<body>

    <div class="wrapper-vertikal">
        <?php include '../../includes/navbar.php'; ?>

        <main class="admin-container">
            <div class="welcome-block">
                <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?> 👋</h2>
                <p>Panel Kendali Utama Pengurus RT 00. Silakan pilih menu di bawah untuk mengelola lingkungan.</p>
            </div>

            <div class="dashboard-grid">
                <a href="keluhan.php" class="stat-card">
                    <div class="stat-icon icon-keluhan">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $count_keluhan['total']; ?></h3>
                        <p>Keluhan Menunggu</p>
                        <span class="stat-link">Proses Keluhan <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </a>

                <a href="forum.php" class="stat-card">
                    <div class="stat-icon icon-forum">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $count_forum['total']; ?></h3>
                        <p>Diskusi Warga</p>
                        <span class="stat-link">Moderasi Forum <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </a>

                <a href="pengumuman.php" class="stat-card">
                    <div class="stat-icon icon-pengumuman">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="status-sistem-text">
                            <i class="fa-solid fa-circle-check"></i> Sistem Aktif
                        </h3>
                        <p class="subtitle-pengumuman">Manajemen Pengumuman</p>
                        <span class="stat-link">Kelola Maklumat <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </a>

                <a href="user.php" class="stat-card">
                    <div class="stat-icon icon-warga">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $count_warga['total']; ?></h3>
                        <p>Warga Terdaftar</p>
                        <span class="stat-link">Lihat Data Warga <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </a>
            </div>
        </main>

        <?php include '../../includes/footer.php'; ?>
    </div>

</body>
</html>