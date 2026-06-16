<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Portal Warga</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-header.php"> 
    <link rel="stylesheet" href="../../assets/css/style-login.css">
    <link rel="stylesheet" href="../../assets/css/style-footer.css">
</head>
<body class="halaman-login-total">

    <div class="wrapper-vertikal">

        <header class="blok-navbar">
            <?php include '../../includes/navbar.php'; ?>
        </header>


        <main class="blok-konten-login">
            
            <div class="back-button-container">
                <a href="../../index.php" class="btn-kembali">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>

            <div class="login-card">
                <div class="card-header">
                    <h2>Masuk ke Akun Warga</h2>
                    <p>Gunakan alamat email dan kata sandi yang telah didaftarkan</p>
                </div>

                <form action="#" method="POST" class="card-body">
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <div class="input-icon-wrapper">
                            <i class="fa-regular fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email" placeholder="Masukkan alamat email Anda" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Kata Sandi</label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" placeholder="Kata sandi" required>
                            <i class="fa-regular fa-eye toggle-password" id="eye-icon"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-masuk-submit">
                        Masuk <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </form>

                <div class="card-footer">
                    <p class="register-text">Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
                    <div class="info-box">
                        <i class="fa-solid fa-circle-info"></i>
                        <p>Akun warga didaftarkan dan diverifikasi oleh pengurus RT. Hubungi ketua RT jika belum memiliki akun.</p>
                    </div>
                </div>
            </div>

        </main>

        <footer class="blok-footer">
            <p>&copy; 2026 Pengurus RT 05/RW 03 · Kelurahan Sukamaju</p>
        </footer>

    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        eyeIcon.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>