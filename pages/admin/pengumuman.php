<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <title>Kelola Pengumuman - Panel Admin RT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
    <link rel="stylesheet" href="../../assets/css/style-pengumuman-admin.css">
</head>
<body>

    <div class="wrapper-vertikal">
        <?php include '../../includes/navbar.php'; ?>

        <div class="area-kembali">
            <a href="dashboard.php" class="btn-kembali-link">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <main class="admin-container">
            
            <div class="admin-header-panel">
                <div class="header-text-group">
                    <h2 class="admin-title">Manajemen Pengumuman RT</h2>
                    <p class="admin-subtitle">Berikut adalah riwayat seluruh maklumat resmi wilayah RT 05</p>
                </div>
                <a href="pengumuman_tambah.php" class="btn-tambah">
                    <i class="fa-solid fa-plus"></i> Buat Pengumuman Baru
                </a>
            </div>

            <div class="admin-box">
                <div class="table-responsive">
                    <table class="table-admin">
                        <thead>
                            <tr>
                                <th class="col-no">No</th>
                                <th class="col-judul">Judul Pengumuman</th>
                                <th class="col-isi">Isi Pengumuman</th>
                                <th class="col-aksi">Aksi Kendali</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = "SELECT * FROM pengumuman ORDER BY id_pengumuman DESC";
                            $eksekusi = mysqli_query($conn, $query);

                            if (mysqli_num_rows($eksekusi) > 0) {
                                while ($row = mysqli_fetch_assoc($eksekusi)) {
                                    $tgl_buat = date('d M Y', strtotime($row['tanggal_pengumuman']));
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <strong class="judul-text"><?= htmlspecialchars($row['judul_pengumuman']); ?></strong>
                                            <br>
                                            <span class="date-text">
                                                <i class="fa-regular fa-calendar"></i> <?= $tgl_buat; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            $isi_bersih = strip_tags($row['isi_pengumuman']);
                                            if (strlen($isi_bersih) > 160) {
                                                echo nl2br(htmlspecialchars(substr($isi_bersih, 0, 160))) . '...';
                                            } else {
                                                echo nl2br(htmlspecialchars($isi_bersih));
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons-group">
                                                <a href="pengumuman_edit.php?id=<?= $row['id_pengumuman']; ?>" class="btn-aksi-edit">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </a>
                                                <a href="../../process/admin/pengumuman_process.php?action=delete&id=<?= $row['id_pengumuman']; ?>" 
                                                   class="btn-aksi-hapus" 
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini secara permanen?')">
                                                    <i class="fa-solid fa-trash"></i> Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="4" class="table-empty">Belum ada pengumuman yang diterbitkan oleh admin.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <?php include '../../includes/footer.php'; ?>
    </div>

</body>
</html>