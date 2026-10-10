<?php
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
global $conn;

// Validasi login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil ID keluhan dari URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_keluhan = mysqli_real_escape_string($conn, $_GET['id']);
$id_user_login = $_SESSION['id_user'];

// Query ambil data keluhan, pastikan milik user yang login DAN statusnya masih Menunggu
$query = "SELECT * FROM keluhan WHERE id_keluhan = '$id_keluhan' AND id_user = '$id_user_login'";
$eksekusi = mysqli_query($conn, $query);

if (mysqli_num_rows($eksekusi) == 0) {
    die("Data keluhan tidak ditemukan atau Anda tidak memiliki akses.");
}

$data = mysqli_fetch_assoc($eksekusi);

// KUNCI UTAMA DI SERVER: Jika status sudah bukan Menunggu, blokir akses edit!
if ($data['status'] !== 'Menunggu') {
    die("Keluhan tidak dapat diedit karena sedang diproses atau telah selesai.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Keluhan - Portal Warga</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
    <link rel="stylesheet" href="../../assets/css/style-keluhan.css">
</head>
<body class="halaman-keluhan-total">

    <div class="wrapper-vertikal">
        <?php include '../../includes/navbar.php'; ?>

        <main class="keluhan-container">
            <div class="form-complaint-card">
                
                <div class="form-card-header">
                    <h3>Edit Pengajuan Keluhan (<?= htmlspecialchars($data['kode_keluhan']); ?>)</h3>
                    <p>Perbarui informasi keluhan Anda selama status masih menunggu</p>
                </div>
                
                <form action="../../process/keluhan_edit_process.php" method="POST" class="form-card-body">
                    <input type="hidden" name="id_keluhan" value="<?= $data['id_keluhan']; ?>">

                    <div class="form-group">
                        <label for="judul_keluhan">Judul Keluhan <span class="req">*</span></label>
                        <input type="text" id="judul_keluhan" name="judul_keluhan" value="<?= htmlspecialchars($data['judul_keluhan']); ?>" required class="input-field">
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi Kejadian <span class="req">*</span></label>
                        <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($data['lokasi']); ?>" required class="input-field">
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Uraian Keluhan (Deskripsi) <span class="req">*</span></label>
                        <textarea id="deskripsi" name="deskripsi" rows="6" required class="input-field-area"><?= htmlspecialchars($data['deskripsi']); ?></textarea>
                    </div>

                    <div class="form-actions-footer">
                        <a href="index.php" class="btn-cancel-form">Batal</a>
                        <button type="submit" name="update_keluhan" class="btn-submit-form">Simpan Perubahan</button>
                    </div>
                </form>
                
            </div>
        </main>

        <?php include '../../includes/footer.php'; ?>
    </div>

</body>
</html>