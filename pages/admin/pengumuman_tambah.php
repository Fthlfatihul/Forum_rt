<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../../index.php?pesan=akses_ditolak");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pengumuman Baru - Panel Admin RT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
    <link rel="stylesheet" href="../../assets/css/style-pengumuman-admin-tambah.css">
</head>
<body>

    <div class="wrapper-vertikal">
        <?php include '../../includes/navbar.php'; ?>


        <main class="admin-container">
            <div >
                <h2 >Buat Pengumuman Baru</h2>
                <p >Tuliskan maklumat atau berita resmi yang ingin disampaikan kepada seluruh warga RT 05</p>
            </div>

            <div class="form-box">
                <form action="../../process/admin/pengumuman_process.php?action=insert" method="POST">
                    
                    <div class="form-group">
                        <label for="judul_pengumuman">Judul Pengumuman</label>
                        <input type="text" id="judul_pengumuman" name="judul_pengumuman" class="form-control" placeholder="Contoh: Kerja Bakti Massal Kebersihan Lingkungan" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="isi_pengumuman">Isi Pengumuman</label>
                        <textarea id="isi_pengumuman" name="isi_pengumuman" class="form-control" rows="8" placeholder="Tulis rincian informasi, waktu, and tempat di sini secara lengkap..." required></textarea>
                    </div>

                    <div class="btn-container">
                        <a href="pengumuman.php" class="btn-batal">Batal</a>
                        <button type="submit" name="submit_pengumuman" class="btn-simpan">
                            <i class="fa-solid fa-paper-plane"></i> Publikasikan
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <?php include '../../includes/footer.php'; ?>
    </div>
</body>
</html>