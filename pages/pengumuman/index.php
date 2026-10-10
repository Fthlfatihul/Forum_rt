<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/database.php';
global $conn;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Pengumuman - Forum Warga</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
    <link rel="stylesheet" href="../../assets/css/style-pengumuman.css"> 
</head>
<body class="halaman-pengumuman-total">

    <div class="wrapper-vertikal">
        <?php include '../../includes/navbar.php'; ?>

        <main class="pengumuman-container">
            <div class="pengumuman-header">
                <h2>Papan Pengumuman</h2>
                <p>Informasi resmi dari pengurus RT 05 / RW 03</p>
            </div>

            <div class="accordion-wrapper">
                <?php
                // Query ambil pengumuman gabung dengan data pembuat dari tabel users
                $query = "SELECT p.*, u.nama FROM pengumuman p 
                          INNER JOIN users u ON p.id_user = u.id_user 
                          ORDER BY p.id_pengumuman DESC";
                
                $eksekusi = mysqli_query($conn, $query);

                if (mysqli_num_rows($eksekusi) > 0) {
                    $is_first = true; // Flag penanda agar item pertama otomatis terbuka
                    
                    while ($row = mysqli_fetch_assoc($eksekusi)) {
                        $tanggal_tampil = date('j M Y', strtotime($row['tanggal_pengumuman']));
                        
                        // Menentukan class active dan bentuk awal ikon panah untuk item pertama
                        $active_class = $is_first ? 'active' : '';
                        $arrow_class  = $is_first ? 'fa-chevron-up' : 'fa-chevron-down';
                        ?>
                        
                        <div class="accordion-item <?= $active_class; ?>">
                            <div class="accordion-title-bar">
                                <div class="title-meta">
                                    <span class="ann-date">
                                        <i class="fa-regular fa-calendar"></i> <?= $tanggal_tampil; ?>
                                    </span>
                                    <h4><?= htmlspecialchars($row['judul_pengumuman']); ?></h4>
                                </div>
                                <i class="fa-solid <?= $arrow_class; ?> arrow-icon"></i>
                            </div>
                            <div class="accordion-content">
                                <p class="content-text">
                                    <?= nl2br(htmlspecialchars($row['isi_pengumuman'])); ?>
                                </p>
                                <div class="content-footer">
                                    <p>Diumumkan oleh: <strong><?= htmlspecialchars($row['nama']); ?></strong> · <?= $tanggal_tampil; ?></p>
                                </div>
                            </div>
                        </div>

                        <?php
                        $is_first = false; // Matikan flag setelah item pertama sukses dirender
                    }
                } else {
                    ?>
                    <div class="pengumuman-empty-state">
                        <i class="fa-solid fa-bullhorn empty-icon"></i>
                        <p class="empty-text">Belum ada pengumuman resmi saat ini.</p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </main>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <script>
        const accordionItems = document.querySelectorAll('.accordion-item');

        accordionItems.forEach(item => {
            const titleBar = item.querySelector('.accordion-title-bar');
            
            titleBar.addEventListener('click', () => {
                const arrow = item.querySelector('.arrow-icon');
                
                // Toggle class 'active' pada item yang diklik
                item.classList.toggle('active');
                
                // Ganti ikon panah atas/bawah sesuai status aktif saat ini
                if (item.classList.contains('active')) {
                    arrow.classList.remove('fa-chevron-down');
                    arrow.classList.add('fa-chevron-up');
                } else {
                    arrow.classList.remove('fa-chevron-up');
                    arrow.classList.add('fa-chevron-down');
                }
            });
        });
    </script>
</body>
</html>