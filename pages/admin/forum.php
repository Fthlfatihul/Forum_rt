<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Keamanan: Hanya Admin yang boleh masuk
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../../index.php?pesan=akses_ditolak");
    exit;
}

require_once '../../config/database.php';
global $conn;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderasi Forum - Admin RT 05</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-forum.css">
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
    <link rel="stylesheet" href="../../assets/css/style-forum-admin.css">
</head>
<body class="halaman-diskusi-total">

    <div class="wrapper-vertikal">
        <?php include '../../includes/navbar.php'; ?>
        
        <div class="area-kembali">
            <a href="dashboard.php" class="btn-kembali-link">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <main class="forum-container">
            
            <div class="forum-main-header">
                <div class="header-titles">
                    <h2>Panel Moderasi Forum Warga</h2>
                    <p>Pantau, tertibkan, dan hapus konten diskusi yang melanggar ketertiban RT 05</p>
                </div>
            </div>

            <div class="admin-header-info">
                <i class="fa-solid fa-shield-halved badge-moderator-icon"></i>
                <span><strong>Mode Moderator Aktif:</strong> Anda dapat menghapus topik atau komentar warga secara mutlak jika terbukti melanggar aturan lingkungan.</span>
            </div>

            <div class="discussion-feed-wrapper">
                <?php
                // Ambil semua data postingan warga
                $query_postingan = "SELECT p.*, u.nama, 
                                    (SELECT COUNT(*) FROM komentar k WHERE k.id_postingan = p.id_postingan) AS total_komentar 
                                    FROM postingan p 
                                    INNER JOIN users u ON p.id_user = u.id_user 
                                    ORDER BY p.id_postingan DESC";
                
                $eksekusi_postingan = mysqli_query($conn, $query_postingan);

                if (mysqli_num_rows($eksekusi_postingan) > 0) {
                    while ($post = mysqli_fetch_assoc($eksekusi_postingan)) {
                        $id_post = $post['id_postingan'];
                        $tanggal_post = date('j M Y', strtotime($post['tanggal_posting']));
                        $jam_post     = date('H:i', strtotime($post['tanggal_posting'])) . ' WITA';
                        ?>

                        <div class="discussion-card-item">
                            <div class="card-main-layout">
                                <div class="card-body-content">
                                    <div class="card-header-flex">
                                        <h3 class="topic-title"><?= htmlspecialchars($post['judul_postingan']); ?></h3>
                                        
                                        <a href="../../process/admin/forum_process.php?action=delete_post&id=<?= $id_post; ?>" 
                                           class="btn-moderasi-hapus-post" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus topik diskusi ini secara permanen beserta seluruh komentarnya?')">
                                            <i class="fa-solid fa-trash-can"></i> Hapus Topik
                                        </a>
                                    </div>
                                    
                                    <p class="topic-snippet"><?= nl2br(htmlspecialchars($post['isi_postingan'])); ?></p>
                                    
                                    <div class="topic-author-footer">
                                        <span><i class="fa-regular fa-user"></i> Pembuat: <strong><?= htmlspecialchars($post['nama']); ?></strong></span>
                                        <span><i class="fa-regular fa-calendar"></i> <?= $tanggal_post; ?></span>
                                        <span><i class="fa-regular fa-clock"></i> <?= $jam_post; ?></span>
                                        
                                        <button class="btn-comment-trigger">
                                            <i class="fa-regular fa-comment"></i> <?= $post['total_komentar']; ?> Komentar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="comments-expansion-block">
                                <div class="comments-list-wrapper">
                                    <?php
                                    // Ambil data komentar khusus postingan ini
                                    $query_komentar = "SELECT k.*, u.nama FROM komentar k 
                                                       INNER JOIN users u ON k.id_user = u.id_user 
                                                       WHERE k.id_postingan = '$id_post' 
                                                       ORDER BY k.id_komentar ASC";
                                    
                                    $eksekusi_komentar = mysqli_query($conn, $query_komentar);

                                    if (mysqli_num_rows($eksekusi_komentar) > 0) {
                                        while ($komen = mysqli_fetch_assoc($eksekusi_komentar)) {
                                            $waktu_komen = date('j M Y, H:i', strtotime($komen['tanggal_komentar']));
                                            ?>
                                            <div class="comment-row">
                                                <div class="comment-avatar"><i class="fa-regular fa-user"></i></div>
                                                <div class="comment-bubble">
                                                    <div class="comment-meta">
                                                        <strong><?= htmlspecialchars($komen['nama']); ?></strong> 
                                                        <span class="comment-date-text"><?= $waktu_komen; ?></span>
                                                        
                                                        <a href="../../process/admin/forum_process.php?action=delete_comment&id=<?= $komen['id_komentar']; ?>" 
                                                           class="btn-moderasi-hapus-komen"
                                                           onclick="return confirm('Hapus komentar ini?')">
                                                            <i class="fa-solid fa-xmark"></i> Hapus Komentar
                                                        </a>
                                                    </div>
                                                    <p class="comment-text-content"><?= nl2br(htmlspecialchars($komen['isi_komentar'])); ?></p>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo '<p class="comment-empty">Belum ada komentar pada topik ini.</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="forum-empty-box">Belum ada topik diskusi dari warga.</div>';
                }
                ?>
            </div>
        </main>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <script>
        // Toggle Expand Komentar secara dinamis
        document.querySelectorAll('.btn-comment-trigger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const cardItem = this.closest('.discussion-card-item');
                cardItem.classList.toggle('topic-expanded');
                this.classList.toggle('active');
            });
        });
    </script>
</body>
</html>