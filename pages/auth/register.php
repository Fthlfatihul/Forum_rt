<?php
require'../../config/database.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Daftar Akun - Portal Warga</title>
    <link rel="stylesheet" href="../../assets/css/style-navbar.css">
    <link rel="stylesheet" href="../../assets/css/style-register.css">

</head>
<body class="halaman-register-total">
    <div class="wrapper-vertikal">
        <header class="blok-navbar">
            <?php include '../../includes/navbar.php';?>    
        </header>
        <main class="blok-konten-register">
            <div class="register-card">
                <div class="card-header">
                    <h2>Pendaftaran Akun Warga</h2>
                    <p>Isi data diri Anda untuk membuat akun baru</p>
                </div>

                <div class="card-body">
                    <form action="../../process/register_process.php" method="post">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap <span class="required">*</span></label>
                            <div class="input-icon-wrapper">
                                <i class="fa-regular fa-circle-user input-icon"></i>
                                <input type="text" id="nama" name="nama"  placeholder="Nama Sesuai Ktp" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Alamat Email <span class="required">*</span></label>
                            <div class="input-icon-wrapper">
                                <i class="fa-regular fa-envelope input-icon"></i>
                                <input type="email" id="email" name="email" placeholder="Masukkan alamat email aktif" required>
                            </div>
                        </div>

                    <div class="form-group">
                        <label for="password">Kata Sandi <span class="required">*</span></label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required>
                            <i class="fa-regular fa-eye toggle-password" onclick="togglePass('password', this)"></i>
                        </div>
                    </div>

                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Kata Sandi <span class="required">*</span></label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-lock input-icon"></i>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi kata sandi" required>
                                <i class="fa-regular fa-eye toggle-password" onclick="togglePass('confirm_password', this)"></i>
                            </div>
                        </div>

                        <button type="submit" name="register" class="btn-daftar-submit">Daftar Akun</button>
                    </form>
                </div>
                
                <div class="card-footer">
                    <p class="login-link-text">Sudah Punya Akun? <a href="login.php">Masuk</a></p>
                </div>
            </div>

        </main>

        <div class="simple-copyright">
            <p>&copy; 2026 Pengurus RT 00/RW 00 · Kelurahan Sudiang</p>
        </div>
    </div>
    
    <script>
        function togglePass(id, icon){
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>