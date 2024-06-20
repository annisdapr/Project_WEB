<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil parameter category dari query string
$category = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Query untuk mengambil data UKM berdasarkan kategori
$sql = "SELECT * FROM ukm WHERE kategori = ?";
$stmt = $koneksi->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();

    // Data UKM
    $ukms = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ukms[] = $row;
        }
    }

    $stmt->close();
} else {
    // Jika statement gagal dipersiapkan, beri pesan kesalahan
    die("Failed to prepare statement: " . $conn->error);
}

$koneksi->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail UKM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style>
        .foot{
                display: flex;
                padding: 12px;
                justify-content: space-between;
                align-items: flex-start;
                flex: 1 0 0;
        }
        .card {
            cursor: pointer;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #8BABB8;">
        <a class="navbar-brand" href="#"><img src="image/logofix1.png" alt=""></a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUKM" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        UKM
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownUKM">
                        <a class="dropdown-item" href="lihat_ukm.php?kategori=Agama">Agama</a>
                        <a class="dropdown-item" href="lihat_ukm.php?kategori=Olahraga">Olahraga</a>
                        <a class="dropdown-item" href="lihat_ukm.php?kategori=Kemahasiswaan">Kemahasiswaan</a>
                        <a class="dropdown-item" href="lihat_ukm.php?kategori=Minat & Bakat">Minat & Bakat</a>
                        <a class="dropdown-item" href="lihat_ukm.php?kategori=Seni">Seni</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Informasi</a>
                </li>
            </ul>
            <form class="form-inline ml-auto">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><img src="image/search.png" alt=""></button>
            </form>
            <ul class="navbar-nav ml-2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="uploaded_avatars/<?php echo $_SESSION['avatar']; ?>" class="rounded-circle" alt="" width="30" height="30"> <?php echo $_SESSION['name']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                            <a class="dropdown-item" href="profile.php">Profil</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="login.php">Masuk</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
<!-- End of Navbar -->
    <div class="container">
        <div class="row mt-4">
            <?php if (!empty($ukms)): ?>
                <?php foreach ($ukms as $ukm): ?>
                    <div class="col-md-4">
                        <div class="card mb-4" onclick="window.location.href='#'">
                        <img src="<?php echo htmlspecialchars($ukm['avatar']); ?>" class="card-img-top" alt="UKM Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($ukm['name']); ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">Tidak ada UKM dalam kategori ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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