<?php
session_start();

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include "koneksi.php";

// Mendapatkan jumlah pengguna, UKM, dan event untuk dashboard
$user_count = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='student'");
$ukm_count = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM ukm");
$event_count = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM events");

$user_count = mysqli_fetch_assoc($user_count)['total'];
$ukm_count = mysqli_fetch_assoc($ukm_count)['total'];
$event_count = mysqli_fetch_assoc($event_count)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="buat_akun_ukm.php">Create Akun UKM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Welcome Admin</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo $user_count; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total UKMs</h5>
                        <p class="card-text"><?php echo $ukm_count; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Events</h5>
                        <p class="card-text"><?php echo $event_count; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tambahkan fitur admin lainnya di sini -->
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
