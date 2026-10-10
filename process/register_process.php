<?php

require_once '../config/database.php';
global $conn;

if (isset($_POST['register'])) {

    // Mengambil data dari form
    $nama                 = trim($_POST['nama']);
    $email                = trim($_POST['email']);
    $password             = $_POST['password'];
    $konfirmasi_password  = $_POST['confirm_password'];

    
    // VALIDASI
    // cek apakah data sudah terisi
    if (empty($nama) || empty($email) || empty($password) || empty($konfirmasi_password)) {

        echo "<script>
                alert('Semua data wajib diisi!');
                window.history.back();
              </script>";
        exit;
    }

    // filter email sesuai format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "<script>
                alert('Format email tidak valid!');
                window.history.back();
              </script>";
        exit;
    }
    // cek password apakah sesuai dengan konfirmasi password
    if ($password != $konfirmasi_password) {

        echo "<script>
                alert('Konfirmasi password tidak sesuai!');
                window.history.back();
              </script>";
        exit;
    }

   
    // CEK EMAIL
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($cek) > 0) {

        echo "<script>
                alert('Email sudah terdaftar!');
                window.history.back();
              </script>";
        exit;
    }

    // hash Password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    
    $query = "INSERT INTO users (nama, email, password, peran)
              VALUES ('$nama', '$email', '$password_hash', 'warga')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Registrasi berhasil!');
                window.location='../pages/auth/login.php';
              </script>";

    } else {
        echo "<script>
                alert('Registrasi gagal!');
                window.history.back();
              </script>";
    }
}