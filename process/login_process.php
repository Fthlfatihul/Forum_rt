<?php
// echo "<pre>";
// print_r($_POST);
// die();

session_start();
require_once '../config/database.php';
global $conn;

if (isset($_POST['login'])) {
    
    // Mengambil data dari form
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validasi input kosong
    if (empty($email) || empty($password)) {

        echo "<script>
                alert('Email dan Password wajib diisi!');
                window.history.back();
              </script>";
        exit;
    }
    

    // Cari email
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  
    if (mysqli_num_rows($query) == 1) {

        $user = mysqli_fetch_assoc($query);
    
        // Verifikasi password
        if (password_verify($password, $user['password'])) {

            // Simpan session
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['peran'] = $user['peran'];

            // Redirect berdasarkan peran
            if ($user['peran'] == "admin") {
                header("Location: ../pages/admin/dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            echo "<script>
                    alert('Password salah!');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Email tidak terdaftar!');
                window.history.back();
              </script>";
    }
}
?>