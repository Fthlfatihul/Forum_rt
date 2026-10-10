<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

require_once '../../config/database.php';
global $conn;

if (!isset($_GET['id'])) {
    header("Location: pengumuman.php");
    exit;
}

$id_pengumuman = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM pengumuman WHERE id_pengumuman = '$id_pengumuman'";
$hasil = mysqli_query($conn, $query);
$data  = mysqli_fetch_assoc($hasil);

if (!$data) {
    die("Data pengumuman tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengumuman - Admin RT 05</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
    <link rel="stylesheet" href="../../assets/css/style-pengumuman-admin-edit.css">
</head>
<body>
    <div class="wrapper-vertikal">
        <?php include '../../includes/navbar.php'; ?>

        <main class="form-container-box">
            <div style="margin-bottom: 20px;">
                <h2 >Ubah Pengumuman</h2>
                <p>Perbarui data atau isi maklumat lingkungan RT 05</p>
            </div>

            <div class="card-form">
                <form action="../../process/admin/pengumuman_process.php?action=update" method="POST">
                    <input type="hidden" name="id_pengumuman" value="<?= $data['id_pengumuman']; ?>">

                    <div class="form-group">
                        <label>Judul Pengumuman</label>
                        <input type="text" name="judul_pengumuman" value="<?= htmlspecialchars($data['judul_pengumuman']); ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Isi Detail Pengumuman</label>
                        <textarea name="isi_pengumuman" rows="6" required class="form-control" style="resize: vertical;"><?= htmlspecialchars($data['isi_pengumuman']); ?></textarea>
                    </div>

                    <div>
                        <a href="pengumuman.php" class="btn-kembali">Batal</a>
                        <button type="submit" name="update_pengumuman" class="btn-update"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </main>

        <?php include '../../includes/footer.php'; ?>
    </div>
</body>
</html>