<?php
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['id_user']);
global $conn;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Keluhan - Portal Warga</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
    <link rel="stylesheet" href="../../assets/css/style-keluhan.css">
</head>
<body class="halaman-keluhan-total">

    <div class="wrapper-vertikal">
        <?php include '../../includes/navbar.php'; ?>

        <main class="keluhan-container">
            
            <div class="keluhan-main-header">
                <div class="header-titles">
                    <h2>Pengajuan Keluhan</h2>
                    <p>Sampaikan keluhan Anda dan pantau status penanganannya</p>
                </div>
                <div class="toggle-view-buttons">
                    <button class="tab-btn active" id="btnViewDaftar">Daftar Keluhan</button>
                    <button class="tab-btn sub-accent" id="btnViewForm"><i class="fa-solid fa-plus"></i> Ajukan Keluhan</button>
                </div>
            </div>

            <div id="sectionDaftarKeluhan">
                <?php if (!$is_logged_in): ?>
                    <div class="login-status-prompt">
                        <div class="auth-message-box">
                            <i class="fa-solid fa-circle-info"></i>
                            <h3>Login untuk melihat status keluhan</h3>
                            <p>Silakan masuk terlebih dahulu agar Anda dapat melihat detail dan status keluhan Anda.</p>
                            <a href="../auth/login.php" class="btn-login-prompt">Login Sekarang</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="complaint-feed-list">
                        <?php
                        $id_user_login = $_SESSION['id_user'];
                        $query_tampil = "SELECT * FROM keluhan WHERE id_user = '$id_user_login' ORDER BY id_keluhan DESC";
                        $eksekusi_tampil = mysqli_query($conn, $query_tampil);

                        if (mysqli_num_rows($eksekusi_tampil) > 0) {
                            while ($row = mysqli_fetch_assoc($eksekusi_tampil)) {
                                $class_badge = 'waiting';
                                if ($row['status'] == 'Diproses') {
                                    $class_badge = 'process';
                                } elseif ($row['status'] == 'Selesai') {
                                    $class_badge = 'success';
                                }
                                
                                $tanggal = date('j M Y', strtotime($row['tanggal_keluhan']));
                                $update  = date('j M Y', strtotime($row['updated_at']));
                                ?>
                                
                                <div class="complaint-card">
                                    <div class="card-top-row">
                                        <div class="meta-left">
                                            <span class="complaint-id"><?= htmlspecialchars($row['kode_keluhan']); ?></span>
                                            <span class="badge badge-status <?= $class_badge; ?>"><?= htmlspecialchars($row['status']); ?></span>
                                        </div>
                                        <div class="action-buttons">
                                            <?php if ($row['status'] == 'Menunggu') : ?>
                                                <a href="edit.php?id=<?= $row['id_keluhan']; ?>" class="btn-edit-keluhan">
                                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <h3 class="complaint-title"><?= htmlspecialchars($row['judul_keluhan']); ?></h3>
                                    <p class="complaint-desc"><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></p>
                                    
                                    <div class="card-meta-footer">
                                        <span><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($row['lokasi']); ?></span>
                                        <span><i class="fa-regular fa-calendar"></i> <?= $tanggal; ?></span>
                                        <?php if ($row['updated_at'] != $row['tanggal_keluhan']) : ?>
                                            <span><i class="fa-solid fa-rotate"></i> Update: <?= $update; ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (!empty($row['catatan_admin'])) : ?>
                                        <div class="rt-response-box">
                                            <p><strong>Catatan RT:</strong> <?= nl2br(htmlspecialchars($row['catatan_admin'])); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php
                            }
                        } else {
                            ?>
                            <div class="complaint-empty-state">
                                <i class="fa-regular fa-folder-open"></i>
                                <p>Belum ada riwayat pengajuan keluhan.</p>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>

            <div id="sectionFormKeluhan" class="form-section-hidden">
                <div class="form-complaint-card">
                    <div class="form-card-header">
                        <h3>Formulir Pengajuan Keluhan</h3>
                        <p>Isi dengan lengkap dan jelas agar dapat ditangani dengan cepat</p>
                    </div>
                    
                    <form action="../../process/keluhan_process.php" method="POST" class="form-card-body">
                        <div class="form-group">
                            <label for="judul_keluhan">Judul Keluhan <span class="req">*</span></label>
                            <input type="text" id="judul_keluhan" name="judul_keluhan" placeholder="Ringkasan singkat keluhan Anda" required class="input-field">
                        </div>

                        <div class="form-group">
                            <label for="lokasi">Lokasi Kejadian <span class="req">*</span></label>
                            <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: Jl. Melati No. 14, depan warung Pak Joko" required class="input-field">
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Uraian Keluhan (Deskripsi) <span class="req">*</span></label>
                            <textarea id="deskripsi" name="deskripsi" placeholder="Jelaskan keluhan Anda secara rinci: apa yang terjadi, kapan mulai, dan dampaknya..." rows="6" required class="input-field-area"></textarea>
                        </div>

                        <div class="info-blue-box">
                            <i class="fa-solid fa-circle-info"></i>
                            <p>Keluhan akan diproses dalam <strong>1-3 hari kerja</strong>. Anda dapat memantau perkembangannya pada tab Daftar Keluhan.</p>
                        </div>

                        <div class="form-actions-footer">
                            <button type="button" class="btn-cancel-form" id="btnBatalAction">Batal</button>
                            <button type="submit" name="kirim_keluhan" class="btn-submit-form">Kirim Keluhan</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <script>
        const btnViewDaftar = document.getElementById('btnViewDaftar');
        const btnViewForm = document.getElementById('btnViewForm');
        const sectionDaftarKeluhan = document.getElementById('sectionDaftarKeluhan');
        const sectionFormKeluhan = document.getElementById('sectionFormKeluhan');
        const btnBatalAction = document.getElementById('btnBatalAction');

        function tunjukkanDaftar() {
            sectionFormKeluhan.style.display = 'none';
            sectionDaftarKeluhan.style.display = 'block';
            btnViewForm.classList.remove('active');
            btnViewDaftar.classList.add('active');
        }

        function tunjukkanForm() {
            sectionDaftarKeluhan.style.display = 'none';
            sectionFormKeluhan.style.display = 'block';
            btnViewDaftar.classList.remove('active');
            btnViewForm.classList.add('active');
        }

        btnViewDaftar.addEventListener('click', tunjukkanDaftar);
        btnViewForm.addEventListener('click', tunjukkanForm);
        btnBatalAction.addEventListener('click', tunjukkanDaftar);
    </script>
</body>
</html>