<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// Cek status login
$is_logged_in = isset($_SESSION['id_user']);

global $conn;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Halaman Utama - Portal RT 05</title>
    <link rel="stylesheet" href="assets/css/style-index.css">
    <link rel="stylesheet" href="assets/css/style-navbar.css">
    <link rel="stylesheet" href="assets/css/style-header.css">
    <link rel="stylesheet" href="assets/css/style-footer.css">
</head>

<body>

    <div class="wrapper-vertikal">
        <?php
        include 'includes/navbar.php';
        include 'includes/header.php';
        ?>

        <div class="dashboard-container">

            <div class="main-content-column">
                <div class="card-section">
                    <div class="card-section-header">
                        <h3>Pengumuman Terbaru</h3>
                        <a href="pages/pengumuman/index.php" class="btn-lihat-semua">Lihat semua <i class="fa-solid fa-chevron-right"></i></a>
                    </div>

                    <div class="card-section-body">
                        <?php

                        $query_pengumuman = "SELECT * FROM pengumuman ORDER BY id_pengumuman DESC LIMIT 3";
                        $eksekusi_pengumuman = mysqli_query($conn, $query_pengumuman);

                        if (mysqli_num_rows($eksekusi_pengumuman) > 0) {
                            $counter = 0;
                            while ($ann = mysqli_fetch_assoc($eksekusi_pengumuman)) {
                                $counter++;

                                $dot_class   = ($counter === 1) ? 'red-dot' : 'blue-dot';
                                $tgl_format  = date('j Jun Y', strtotime($ann['tanggal_pengumuman']));

                                // Membuat potongan teks sinopsis/snippet isi pengumuman
                                $snippet     = substr(strip_tags($ann['isi_pengumuman']), 0, 120);
                                $snippet    .= (strlen($ann['isi_pengumuman']) > 120) ? '...' : '';
                        ?>
                                <div class="announcement-item">
                                    <div class="announcement-title">
                                        <span class="dot <?= $dot_class; ?>"></span>
                                        <h4><?= htmlspecialchars($ann['judul_pengumuman']); ?></h4>
                                        <?php if ($counter === 1): ?>
                                            <span class="badge badge-important">Baru</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="announcement-snippet"><?= htmlspecialchars($snippet); ?></p>
                                    <span class="announcement-date"><i class="fa-regular fa-calendar"></i> <?= $tgl_format; ?></span>
                                </div>

                        <?php
                            }
                        } else {
                            echo '<div style="padding: 20px; color: #94a3b8; text-align: center; font-size: 14px;">Belum ada pengumuman resmi terbaru.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="sidebar-column">

                <div class="widget-card">
                    <div class="widget-header">
                        <h4>Status Keluhan</h4>
                        <a href="pages/keluhan/index.php" class="widget-link">Ajukan <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="widget-body">
                        <?php if ($is_logged_in) : ?>
                            <?php
                            $id_user_login = $_SESSION['id_user'];
                            $query_widget = "SELECT * FROM keluhan 
                                             WHERE id_user = '$id_user_login' 
                                             ORDER BY id_keluhan DESC LIMIT 3";
                            $eksekusi_widget = mysqli_query($conn, $query_widget);

                            if (mysqli_num_rows($eksekusi_widget) > 0) {
                                while ($row = mysqli_fetch_assoc($eksekusi_widget)) {
                                    $class_badge = 'status-process';
                                    $icon_status = 'fa-regular fa-clock';
                                    $class_icon  = 'icon-process';

                                    if ($row['status'] == 'Selesai') {
                                        $class_badge = 'status-success';
                                        $icon_status = 'fa-regular fa-circle-check';
                                        $class_icon  = 'icon-success';
                                    } elseif ($row['status'] == 'Menunggu') {
                                        $class_badge = 'status-waiting';
                                        $icon_status = 'fa-regular fa-hourglass-half';
                                        $class_icon  = 'icon-waiting';
                                    }
                            ?>
                                    <div class="status-item">
                                        <div class="status-info">
                                            <span class="status-icon <?= $class_icon; ?>">
                                                <i class="<?= $icon_status; ?>"></i>
                                            </span>
                                            <div>
                                                <h5><?= htmlspecialchars($row['kode_keluhan']); ?></h5>
                                                <p><?= htmlspecialchars($row['judul_keluhan']); ?></p>
                                            </div>
                                        </div>
                                        <span class="badge-status <?= $class_badge; ?>">
                                            <?= htmlspecialchars($row['status']); ?>
                                        </span>
                                    </div>
                                <?php
                                }
                            } else {
                                ?>
                                <div style="text-align: center; padding: 15px; color: #94a3b8; font-size: 13px;">
                                    <p style="margin: 0;">Belum ada keluhan.</p>
                                </div>
                            <?php
                            }
                            ?>
                        <?php else : ?>
                            <div class="guest-status">
                                <i class="fa-solid fa-lock"></i>
                                <h4>Login Diperlukan</h4>
                                <p>Masuk terlebih dahulu untuk melihat status keluhan dan mengajukan laporan baru.</p>
                                <div class="guest-btn">
                                    <a href="pages/auth/login.php" class="btn-login-widget">Masuk</a>
                                    <a href="pages/auth/register.php" class="btn-register-widget">Daftar</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="widget-card">
                    <div class="widget-header">
                        <h4>Diskusi Terkini</h4>
                        <a href="pages/forum/index.php" class="widget-link">Forum <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="widget-body">
                        <?php
                        // Query Mengambil informasi Diskusi
                        $query_forum = "SELECT p.*, u.nama FROM postingan p 
                                        INNER JOIN users u ON p.id_user = u.id_user 
                                        ORDER BY p.id_postingan DESC LIMIT 3";
                        $eksekusi_forum = mysqli_query($conn, $query_forum);

                        if (mysqli_num_rows($eksekusi_forum) > 0) {
                            while ($forum = mysqli_fetch_assoc($eksekusi_forum)) {
                                // Logika penentu waktu lampau sederhana
                                $waktu_post = date('d M Y', strtotime($forum['tanggal_posting']));

                                // Menghitung jumlah komentar secara real-time
                                $id_f = $forum['id_postingan'];
                                $q_komentar = "SELECT COUNT(*) as total FROM komentar WHERE id_postingan = '$id_f'";
                                $res_komentar = mysqli_query($conn, $q_komentar);
                                $total_komentar = 0;
                                if ($res_komentar) {
                                    $data_k = mysqli_fetch_assoc($res_komentar);
                                    $total_komentar = $data_k['total'];
                                }
                        ?>

                                <div class="discussion-item" onclick="location.href='pages/forum/detail.php?id=<?= $forum['id_postingan']; ?>'" style="cursor: pointer;">
                                    <h5><?= htmlspecialchars($forum['judul_postingan']); ?></h5>
                                    <p><?= htmlspecialchars($forum['nama']); ?> · <i class="fa-regular fa-comment"></i> <?= $total_komentar; ?> · <span style="font-size: 11px;"><?= $waktu_post; ?></span></p>
                                </div>

                        <?php
                            }
                        } else {
                            echo '<div style="padding: 15px; color: #94a3b8; text-align: center; font-size: 13px;">Belum ada diskusi aktif.</div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="widget-card contact-card">
                    <h4>Kontak RT 00</h4>
                    <div class="contact-info">
                        <i class="fa-regular fa-user"></i>
                        <div>
                            <span class="role-title">Ketua RT</span>
                            <h5>Bpk. Agus</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
</body>

</html>