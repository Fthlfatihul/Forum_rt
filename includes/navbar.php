<?php
define('BASE_URL', '/Project/Forum-rt/');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Tambahkan link Font Awesome di header jika belum ada untuk memunculkan ikon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style-navbar.css">

<nav class="navbar-container">
    <!-- Bagian Kiri: Logo dan Nama RT -->
    <div class="nav-brand">
        <div class="brand-logo">RT</div>
        <div class="brand-text">
            <h2>Forum Warga RT 00</h2>
            <p>RW 00 · Kel. Sudiang · Kec. Biringkanaya</p>
        </div>
    </div>

    <!-- Bagian Tengah: Menu Navigasi -->
    <ul class="nav-menu">
        <li>
            <a href="<?= BASE_URL ?>index.php" class="nav-item ">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>pages/pengumuman/index.php" class="nav-item ">
                <i class="fa-solid fa-bullhorn"></i> Pengumuman
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/pages/forum/index.php" class="nav-item ">
                <i class="fa-regular fa-comments"></i> Forum Diskusi
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>pages/keluhan/index.php" class="nav-item ">
                <i class="fa-regular fa-file-lines"></i> Pengajuan Keluhan
            </a>
        </li>
    </ul>

    <!-- Bagian Kanan: Tombol Masuk -->
    
        
    <div class="nav-action">
    <?php if (isset($_SESSION['id_user'])) : ?>
        <div class="user-info">
            <span class="user-name">
                <i class="fa-solid fa-circle-user user-icon"></i>
                <?= htmlspecialchars($_SESSION['nama']); ?>
            </span>
            <a href="<?= BASE_URL ?>process/logout.php" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    <?php else : ?>
        <a href="<?= BASE_URL ?>pages/auth/login.php" class="btn-masuk">
            <i class="fa-solid fa-right-to-bracket"></i> Masuk
        </a>
    <?php endif; ?>
</div>
</nav>