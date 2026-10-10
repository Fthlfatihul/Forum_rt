<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Akses: Hanya Admin yang boleh masuk
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
    <title>Kelola Keluhan Warga - Admin RT 05</title>
    <link class="css-link" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-keluhan-admin.css">
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
            <div class="page-header">
                <h2>Kelola Keluhan Warga</h2>
                <p>Pantau dan tindaklanjuti setiap aspirasi, aduan, atau keluhan dari warga RT 05</p>
            </div>

            <div class="admin-box">
                <div class="table-responsive">
                    <table class="table-admin">
                        <thead>
                            <tr>
                                <th class="col-no">No</th>
                                <th class="col-warga">Warga / Tanggal</th>
                                <th class="col-isi">Isi Aduan / Keluhan</th>
                                <th class="col-status">Status</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = "SELECT k.*, u.nama FROM keluhan k 
                                      INNER JOIN users u ON k.id_user = u.id_user 
                                      ORDER BY k.id_keluhan DESC";
                            $eksekusi = mysqli_query($conn, $query);

                            if (mysqli_num_rows($eksekusi) > 0) {
                                while ($row = mysqli_fetch_assoc($eksekusi)) {
                                    $id_keluhan = $row['id_keluhan'];
                                    $tgl_keluhan = date('d M Y', strtotime($row['tanggal_keluhan']));
                                    
                                    $status_class = 'status-menunggu';
                                    if ($row['status'] === 'Diproses') $status_class = 'status-diproses';
                                    if ($row['status'] === 'Selesai') $status_class = 'status-selesai';
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <strong class="pelapor-nama"><?= htmlspecialchars($row['nama']); ?></strong>
                                            <br>
                                            <span class="date-text">
                                                <i class="fa-regular fa-calendar"></i> <?= $tgl_keluhan; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="keluhan-judul"><?= htmlspecialchars($row['judul_keluhan']); ?></strong>
                                            <p class="keluhan-deskripsi"><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></p>
                                            
                                            <?php if (!empty($row['catatan_admin'])) : ?>
                                                <div class="tanggapan-rt-box">
                                                    <strong>Tanggapan RT:</strong> <?= nl2br(htmlspecialchars($row['catatan_admin'])); ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="form-tanggapan-box" id="box-tanggapan-<?= $id_keluhan; ?>">
                                                <form action="../../process/admin/keluhan_process.php" method="POST">
                                                    <input type="hidden" name="id_keluhan" value="<?= $id_keluhan; ?>">
                                                    
                                                    <div class="form-group-inline">
                                                        <label class="form-label-inline">Ubah Status:</label>
                                                        <select name="status" class="form-select-inline">
                                                            <option value="Menunggu" <?= $row['status'] == 'Menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                                                            <option value="Diproses" <?= $row['status'] == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                                                            <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai (Arsip)</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group-inline">
                                                        <label class="form-label-inline">Tulis Tanggapan / Catatan Admin:</label>
                                                        <textarea name="catatan_admin" rows="3" placeholder="Contoh: Baik, besok pagi petugas kebersihan akan memeriksa lokasi..." class="form-textarea-inline"><?= htmlspecialchars($row['catatan_admin']); ?></textarea>
                                                    </div>

                                                    <div class="form-actions-inline">
                                                        <button type="button" onclick="tutupFormTanggapan(<?= $id_keluhan; ?>)" class="btn-tanggapan-batal">Batal</button>
                                                        <button type="submit" name="update_keluhan" class="btn-tanggapan-simpan">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-status <?= $status_class; ?>"><?= $row['status']; ?></span>
                                        </td>
                                        <td>
                                            <button class="btn-tanggapi" onclick="bukaFormTanggapan(<?= $id_keluhan; ?>)">
                                                <i class="fa-regular fa-comment-dots"></i> Tanggapi
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="5" class="table-empty">Belum ada keluhan atau laporan dari warga.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <script>
        function bukaFormTanggapan(id) {
            document.getElementById('box-tanggapan-' + id).style.display = 'block';
        }
        function tutupFormTanggapan(id) {
            document.getElementById('box-tanggapan-' + id).style.display = 'none';
        }
    </script>
</body>
</html>