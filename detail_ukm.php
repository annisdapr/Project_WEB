<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil parameter id dari query string
$ukm_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query untuk mengambil data UKM berdasarkan ID
$sql = "SELECT * FROM ukm WHERE id = ?";
$stmt = $koneksi->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $ukm_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ukm = $result->fetch_assoc();
    
    if (!$ukm) {
        echo "UKM tidak ditemukan.";
        exit;
    }

    $stmt->close();
} else {
    // Jika statement gagal dipersiapkan, beri pesan kesalahan
    die("Failed to prepare statement: " . $koneksi->error);
}

$koneksi->close();
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
        <a class="navbar-brand" href="#"><img src="image/logofix1.png" alt=""></a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
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
                        <img src=<?php echo $_SESSION['avatar']; ?> class="rounded-circle" alt="Avatar" width="30" height="30"> <?php echo $_SESSION['name']; ?>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>