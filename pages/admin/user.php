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
    <title>Kelola Data Warga - Admin RT 05</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
    <link rel="stylesheet" href="../../assets/css/style-warga-admin.css">
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
                <h2>Kelola Data Akun Warga</h2>
                <p>Daftar warga RT 05 yang terdaftar di dalam sistem aplikasi portal lingkungan</p>
            </div>

            <div class="admin-box">
                <div class="table-responsive">
                    <table class="table-admin">
                        <thead>
                            <tr>
                                <th class="col-no">No</th>
                                <th class="col-profil">Profil</th>
                                <th class="col-nama">Nama Lengkap</th>
                                <th class="col-email">Alamat Email</th>
                                <th class="col-akses">Hak Akses</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            // Query hanya mengambil user yang perannya 'warga'
                            $query = "SELECT id_user, nama, email, peran FROM users WHERE peran = 'warga' ORDER BY id_user DESC";
                            $eksekusi = mysqli_query($conn, $query);

                            if (mysqli_num_rows($eksekusi) > 0) {
                                while ($row = mysqli_fetch_assoc($eksekusi)) {
                                    // Mengambil inisial huruf pertama nama untuk avatar
                                    $inisial = strtoupper(substr($row['nama'], 0, 1));
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <div class="avatar-circle"><?= $inisial; ?></div>
                                        </td>
                                        <td><strong class="warga-nama"><?= htmlspecialchars($row['nama']); ?></strong></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><span class="badge-warga"><?= htmlspecialchars($row['peran']); ?></span></td>
                                        <td>
                                            <a href="../../process/admin/user_process.php?action=delete&id=<?= $row['id_user']; ?>" 
                                               class="btn-hapus-warga" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus akun warga bernama <?= htmlspecialchars($row['nama']); ?>? Tindakan ini juga berpotensi menghapus/memutuskan relasi data keluhan dan postingan milik warga tersebut.')">
                                                <i class="fa-solid fa-user-minus"></i> Hapus Akun
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="6" class="table-empty">Belum ada akun warga yang terdaftar.</td></tr>';
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