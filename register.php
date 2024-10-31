<?php
session_start(); 
include "config.php"; 

$fullname = $_POST["fullname"] ?? ''; 
$username = $_POST["username"] ?? ''; 
$password = password_hash($_POST["password"] ?? '', PASSWORD_DEFAULT); // Gunakan password_hash

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($fullname) && !empty($username) && !empty($password)) {
        $query = "INSERT INTO users (fullname, username, password) VALUES ('$fullname', '$username', '$password')"; 

        if (mysqli_query($connect, $query)) {
            $_SESSION["status"] = "Berhasil membuat akun. Silahkan login"; 
            header("Location: index.php"); 
            exit();
        } else {
            $_SESSION["status"] = "gagal"; 
            header("Location: login.php?app=gagal"); 
            exit();
        }
    } else {
        echo "Semua field harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-image {
            background-image: url('img/Growtopia.jpg');
            background-size: cover;
            background-position: center;
            height: 100%;
        }
    </style>
</head>
<body>

<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="row w-100 border rounded overflow-hidden" style="max-width: 800px; height: 500px;">
        <div class="col-md-6 d-none d-md-flex bg-image"></div>
        <div class="col-md-6 p-4 form-container">
            <h4 class="text-center d-flex align-items-center justify-content-center mt-4 mb-4">
                <img src="img/bgl.webp" alt="Logo" class="me-2" style="max-width: 30px;"> 
                Register new account
            </h4>
            <form action="register.php" method="POST"> <!-- Arahkan ke halaman ini sendiri -->
                <div class="mb-2">
                    <label for="fullname" class="form-label">Nama Lengkap</label>
                    <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="mb-2">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username" required>
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Daftar</button>
            </form>
            <div class="text-center mt-3">
                <p class="mb-0">Sudah punya akun? <a href="index.php" class="text-primary">Login disini!</a></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
