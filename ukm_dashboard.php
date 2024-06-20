<?php
session_start();

// Cek apakah user sudah login dan memiliki role ukm
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'ukm') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Ambil data profil UKM
$ukm_id = $_SESSION['user_id'];
$query_ukm = "SELECT * FROM ukm WHERE user_id = $ukm_id";
$result_ukm = mysqli_query($koneksi, $query_ukm);
$ukm = mysqli_fetch_assoc($result_ukm);

// Query untuk mendapatkan ukm_id dari user_id 
$query_ukm_id = "SELECT id FROM ukm WHERE user_id = $ukm_id"; 
$result_ukm_id = mysqli_query($koneksi, $query_ukm_id);
$row = mysqli_fetch_row($result_ukm_id);
$id = (int) $row[0];
// Ambil data event UKM yang sedang berlangsung
$query_events = "SELECT * FROM events WHERE ukm_id = $id AND NOW() BETWEEN start_date AND end_date";
$result_events = mysqli_query($koneksi, $query_events);
$events = mysqli_fetch_all($result_events, MYSQLI_ASSOC);

// Ambil data history event UKM (event yang sudah berakhir)
$query_history = "SELECT * FROM events WHERE ukm_id = $id AND NOW() > end_date";
$result_history = mysqli_query($koneksi, $query_history);
$history = mysqli_fetch_all($result_history, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UKM Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #8BABB8;">
        <a class="navbar-brand" href="#"><img src="image/logofix1.png" alt=""> UKM Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="ukm_dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <img src="<?php echo $ukm['avatar']; ?>" class="img-fluid rounded-circle" alt="UKM Avatar">
            </div>
            <div class="col-md-8">
                <h2><?php echo $ukm['name']; ?></h2>
                <p>Email: <?php echo $ukm['email']; ?></p>
                <p>Instagram: <?php echo $ukm['instagram']; ?></p>
                <p>Deskripsi: <?php echo $ukm['deskripsi']; ?></p>
            </div>
        </div>
        <div class="mt-4">
            <h3>Ongoing Events</h3>
            <div class="d-flex justify-content-end mb-2">
                <a href="buat_event.php" class="btn btn-primary">Tambahkan Acara</a>
            </div>
            <?php if (mysqli_num_rows($result_events) > 0): ?>
                <div class="row">
                    <?php foreach ($events as $event): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?php echo $event['header_image']; ?>" class="card-img-top" alt="Header Image">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $event['name']; ?></h5>
                                    <p class="card-text"><?php echo $event['deskripsi']; ?></p>
                                    <a href="lihat_event_ukm.php?id=<?php echo $event['id']; ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Tidak ada event yang sedang berlangsung saat ini.</div>
            <?php endif; ?>
        </div>
        <div class="mt-4">
            <h3>History</h3>
            <?php if (mysqli_num_rows($result_history) > 0): ?>
                <div class="row">
                    <?php foreach ($history as $event): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?php echo $event['header_image']; ?>" class="card-img-top" alt="Header Image">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $event['name']; ?></h5>
                                    <p class="card-text"><?php echo $event['deskripsi']; ?></p>
                                    <a href="lihat_event_ukm.php?id=<?php echo $event['id']; ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Belum ada event yang selesai.</div>
            <?php endif; ?>
        </div>
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
