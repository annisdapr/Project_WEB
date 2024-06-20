<?php
session_start();

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include "koneksi.php";

// Inisialisasi variabel untuk pesan error atau berhasil
$error_message = '';
$success_message = '';

// Proses form jika ada data yang dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $ukm_name = mysqli_real_escape_string($koneksi, $_POST['ukm_name']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk menyimpan data pengguna baru ke tabel users
    $query_user = "INSERT INTO users (username, name, email, password, role) 
                   VALUES (?, ?, ?, ?, 'ukm')";
    $stmt_user = mysqli_prepare($koneksi, $query_user);
    mysqli_stmt_bind_param($stmt_user, 'ssss', $username, $ukm_name, $email, $hashed_password);
    
    // Lakukan eksekusi query
    if (mysqli_stmt_execute($stmt_user)) {
        // Ambil id yang baru saja di-generate
        $user_id = mysqli_insert_id($koneksi);

        // Query untuk menyimpan data UKM baru ke tabel ukm
        $query_ukm = "INSERT INTO ukm (user_id, name, kategori) 
                      VALUES (?, ?, ?)";
        $stmt_ukm = mysqli_prepare($koneksi, $query_ukm);
        mysqli_stmt_bind_param($stmt_ukm, 'iss', $user_id, $ukm_name, $kategori);
        
        // Lakukan eksekusi query
        if (mysqli_stmt_execute($stmt_ukm)) {
            $success_message = "Akun UKM berhasil dibuat!";
        } else {
            $error_message = "Terjadi kesalahan saat membuat akun UKM: " . mysqli_error($koneksi);
        }

        mysqli_stmt_close($stmt_ukm);
    } else {
        $error_message = "Terjadi kesalahan saat membuat akun: " . mysqli_error($koneksi);
    }

    mysqli_stmt_close($stmt_user);
    mysqli_close($koneksi);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Akun UKM</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .foot{
                display: flex;
                padding: 12px;
                justify-content: space-between;
                align-items: flex-start;
                flex: 1 0 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Create Akun UKM</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php elseif (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <label>Nama UKM:</label>
                <input type="text" class="form-control" name="ukm_name" required>
            </div>
            <div class="form-group">
                <label>Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label>Kategori UKM:</label>
                <select class="form-control" name="kategori" required>
                    <option value="agama">Agama</option>
                    <option value="olahraga">Olahraga</option>
                    <option value="kemahasiswaan">Kemahasiswaan</option>
                    <option value="minat&bakat">Minat & Bakat</option>
                    <option value="seni">Seni</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<footer class="navbar navbar-expand-lg navbar-light" style="background-color: #8BABB8;">
    <div class="foot" style="justify-content: space-between">
        <p style="color: #fff;">© 2024 U-COMM. All rights reserved.</p>
        <div >
            <img src="image\github.svg" alt="" href="#">
            <img src="image\dribbble.svg" alt="" href="#">
            <img src="image\facebook-f.svg" alt="" href="#">
            <img src="image\twitter.svg" alt="" href="#">
        </div>
    </div>
    
</footer>
</html>
