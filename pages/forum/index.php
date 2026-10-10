<?php
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
global $conn;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Diskusi Warga - Portal RT 05</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
    <link rel="stylesheet" href="../../assets/css/style-forum.css">
</head>
<body class="halaman-diskusi-total">

    <div class="wrapper-vertikal">
        <?php include '../../includes/navbar.php'; ?>

        <main class="forum-container">
            
            <div class="forum-main-header">
                <div class="header-titles">
                    <h2>Forum Diskusi Warga</h2>
                    <p>Tempat bertukar gagasan dan aspirasi warga RT 05</p>
                </div>
                <button class="btn-buat-diskusi" id="btnToggleForm">
                    <i class="fa-solid fa-plus"></i> Buat Diskusi Baru
                </button>
            </div>

            <div class="create-topic-card" id="formBuatDiskusi">
                <h3>Buat Topik Diskusi Baru</h3>
                <form action="../../process/forum_process.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="judul_postingan" placeholder="Judul diskusi..." required class="input-flat">
                    </div>
                    <div class="form-group">
                        <textarea name="isi_postingan" placeholder="Jelaskan topik diskusi Anda..." rows="4" required class="input-flat"></textarea>
                    </div>
                    <div class="form-actions-buttons">
                        <button type="button" class="btn-batal" id="btnBatalForm">Batal</button>
                        <button type="submit" name="kirim_postingan" class="btn-posting">Posting Diskusi</button>
                    </div>
                </form>
            </div>

            <div class="discussion-feed-wrapper">
                <?php
                $query_postingan = "SELECT p.*, u.nama, 
                                    (SELECT COUNT(*) FROM komentar k WHERE k.id_postingan = p.id_postingan) AS total_komentar 
                                    FROM postingan p 
                                    INNER JOIN users u ON p.id_user = u.id_user 
                                    ORDER BY p.id_postingan DESC";
                
                $eksekusi_postingan = mysqli_query($conn, $query_postingan);

                if (mysqli_num_rows($eksekusi_postingan) > 0) {
                    while ($post = mysqli_fetch_assoc(($eksekusi_postingan))) {
                        $id_post = $post['id_postingan'];
                        $tanggal_post = date('j M Y', strtotime($post['tanggal_posting']));
                        $jam_post     = date('H:i', strtotime($post['tanggal_posting'])) . ' WITA';
                        ?>

                        <div class="discussion-card-item">
                            <div class="card-main-layout">
                                <div class="card-body-content">
                                    <div class="topic-header-layout">
                                        <h3 class="topic-title" id="text-judul-post-<?= $id_post; ?>">
                                            <?= htmlspecialchars($post['judul_postingan']); ?>
                                        </h3>
                                        
                                        <?php if (isset($_SESSION['id_user']) && $_SESSION['id_user'] == $post['id_user']) : ?>
                                            <div class="post-actions">
                                                <button onclick="pemicuEditPostingan(<?= $id_post; ?>)" class="btn-post-edit"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                                <a href="../../process/forum_hapus_process.php?id=<?= $id_post; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus seluruh topik diskusi ini beserta komentarnya?')" class="btn-post-hapus"><i class="fa-solid fa-trash"></i> Hapus</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <p class="topic-snippet" id="text-isi-post-<?= $id_post; ?>">
                                        <?= nl2br(htmlspecialchars($post['isi_postingan'])); ?>
                                    </p>
                                    
                                    <?php if (isset($_SESSION['id_user']) && $_SESSION['id_user'] == $post['id_user']) : ?>
                                        <form id="form-edit-post-<?= $id_post; ?>" action="../../process/forum_edit_process.php" method="POST" class="form-edit-post-inline">
                                            <input type="hidden" name="id_postingan" value="<?= $id_post; ?>">
                                            <div class="form-group-reset">
                                                <input type="text" name="judul_postingan" value="<?= htmlspecialchars($post['judul_postingan']); ?>" required class="input-edit-judul">
                                            </div>
                                            <div class="form-group-reset">
                                                <textarea name="isi_postingan" rows="4" required class="textarea-edit-isi"><?= htmlspecialchars($post['isi_postingan']); ?></textarea>
                                            </div>
                                            <div class="form-actions-edit">
                                                <button type="button" onclick="batalEditPostingan(<?= $id_post; ?>)" class="btn-edit-batal">Batal</button>
                                                <button type="submit" name="update_postingan" class="btn-edit-simpan">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <div class="topic-author-footer">
                                        <span><i class="fa-regular fa-user"></i> <?= htmlspecialchars($post['nama']); ?></span>
                                        <span><i class="fa-regular fa-calendar"></i> <?= $tanggal_post; ?></span>
                                        <span><i class="fa-regular fa-clock"></i> <?= $jam_post; ?></span>
                                        
                                        <button class="btn-comment-trigger">
                                            <i class="fa-regular fa-comment"></i> <?= $post['total_komentar']; ?> komentar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="comments-expansion-block">
                                <div class="comments-list-wrapper">
                                    <?php
                                    $query_komentar = "SELECT k.*, u.nama FROM komentar k 
                                                       INNER JOIN users u ON k.id_user = u.id_user 
                                                       WHERE k.id_postingan = '$id_post' 
                                                       ORDER BY k.id_komentar ASC";
                                    
                                    $eksekusi_komentar = mysqli_query($conn, $query_komentar);

                                    if (mysqli_num_rows($eksekusi_komentar) > 0) {
                                        while ($komen = mysqli_fetch_assoc($eksekusi_komentar)) {
                                            $waktu_komen = date('j M Y, H:i', strtotime($komen['tanggal_komentar']));
                                            ?>
                                            <div class="comment-row" id="comment-box-<?= $komen['id_komentar']; ?>">
                                                <div class="comment-avatar"><i class="fa-regular fa-user"></i></div>
                                                <div class="comment-bubble">
                                                    <div class="comment-meta">
                                                        <div class="comment-meta-user">
                                                            <strong><?= htmlspecialchars($komen['nama']); ?></strong> 
                                                            <span class="comment-time"><?= $waktu_komen; ?></span>
                                                        </div>
                                                        
                                                        <?php if (isset($_SESSION['id_user']) && $_SESSION['id_user'] == $komen['id_user']) : ?>
                                                            <div class="comment-actions">
                                                                <button onclick="pemicuEditKomentar(<?= $komen['id_komentar']; ?>)" class="btn-comment-edit"><i class="fa-solid fa-pen"></i> Edit</button>
                                                                <a href="../../process/komentar_hapus_process.php?id=<?= $komen['id_komentar']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')" class="btn-comment-hapus"><i class="fa-solid fa-trash"></i> Hapus</a>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <p id="text-komen-<?= $komen['id_komentar']; ?>" class="comment-body-text"><?= nl2br(htmlspecialchars($komen['isi_komentar'])); ?></p>
                                                    
                                                    <form id="form-edit-komen-<?= $komen['id_komentar']; ?>" action="../../process/komentar_edit_process.php" method="POST" class="form-edit-comment-inline">
                                                        <input type="hidden" name="id_komentar" value="<?= $komen['id_komentar']; ?>">
                                                        <input type="text" name="isi_komentar" value="<?= htmlspecialchars($komen['isi_komentar']); ?>" required class="input-edit-comment">
                                                        <button type="submit" name="update_komentar" class="btn-comment-save">Simpan</button>
                                                        <button type="button" onclick="batalEditKomentar(<?= $komen['id_komentar']; ?>)" class="btn-comment-cancel">Batal</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo '<p class="no-comment-text">Belum ada komentar. Jadilah yang pertama menanggapi!</p>';
                                    }
                                    ?>

                                    <form action="../../process/komentar_process.php" method="POST" class="comment-input-form-row">
                                        <input type="hidden" name="id_postingan" value="<?= $id_post; ?>">
                                        <input type="text" name="isi_komentar" placeholder="Tulis komentar Anda..." required class="comment-field-box">
                                        <button type="submit" name="kirim_komentar" class="btn-send-comment">Kirim</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                } else {
                    ?>
                    <div class="forum-empty-state">
                        <i class="fa-regular fa-comments"></i>
                        <p>Belum ada topik diskusi. Yuk, buat diskusi baru sekarang!</p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </main>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <script>
        // Toggle Form Buat Diskusi Baru
        const btnToggleForm = document.getElementById('btnToggleForm');
        const btnBatalForm = document.getElementById('btnBatalForm');
        const formBuatDiskusi = document.getElementById('formBuatDiskusi');

        btnToggleForm.addEventListener('click', () => {
            formBuatDiskusi.style.display = formBuatDiskusi.style.display === 'none' ? 'block' : 'none';
        });

        btnBatalForm.addEventListener('click', () => {
            formBuatDiskusi.style.display = 'none';
        });

        // Toggle Expand Komentar secara dinamis
        document.querySelectorAll('.btn-comment-trigger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const cardItem = this.closest('.discussion-card-item');
                cardItem.classList.toggle('topic-expanded');
                this.classList.toggle('active');
            });
        });
        
        // Pemicu form edit postingan
        function pemicuEditPostingan(id) {
            document.getElementById('text-judul-post-' + id).style.display = 'none';
            document.getElementById('text-isi-post-' + id).style.display = 'none';
            document.getElementById('form-edit-post-' + id).style.display = 'flex';
        }

        function batalEditPostingan(id) {
            document.getElementById('text-judul-post-' + id).style.display = 'block';
            document.getElementById('text-isi-post-' + id).style.display = 'block';
            document.getElementById('form-edit-post-' + id).style.display = 'none';
        }

        // Pemicu form edit komentar
        function pemicuEditKomentar(id) {
            document.getElementById('text-komen-' + id).style.display = 'none';
            document.getElementById('form-edit-komen-' + id).style.display = 'flex';
        }

        function batalEditKomentar(id) {
            document.getElementById('text-komen-' + id).style.display = 'block';
            document.getElementById('form-edit-komen-' + id).style.display = 'none';
        }
    </script>
</body>
</html>