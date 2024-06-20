<?php
session_start();
include 'koneksi.php'; // Sambungkan ke database

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$name = $_SESSION['name'];
$avatar = $_SESSION['avatar'];

if ($role == 'student') {
    $query = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);
    $jurusan = $user['jurusan'];
    $fakultas = $user['fakultas'];
    $nim = $user['nim'];
    $phone = $user['phone'];

    // Query untuk mengambil event yang di-wishlist
    $query_wishlist = "
        SELECT events.id, events.name, events.header_image, events.start_date, events.end_date 
        FROM wishlist 
        JOIN events ON wishlist.event_id = events.id
        WHERE wishlist.user_id = '$user_id'
    ";
    $result_wishlist = mysqli_query($koneksi, $query_wishlist);
    $wishlist_events = mysqli_fetch_all($result_wishlist, MYSQLI_ASSOC);

    // Query untuk mengambil event yang sudah didaftarkan (history)
    $query_history = "
        SELECT events.id, events.name, events.header_image, events.start_date, events.end_date, registrations.registration_date, registrations.status 
        FROM registrations 
        JOIN events ON registrations.event_id = events.id 
        WHERE registrations.user_id = '$user_id'
    ";
    $result_history = mysqli_query($koneksi, $query_history);
    $history_events = mysqli_fetch_all($result_history, MYSQLI_ASSOC);
} elseif ($role == 'ukm') {
    $query = "SELECT * FROM ukm WHERE id = '$user_id'";
    $result = mysqli_query($koneksi, $query);
    $ukm = mysqli_fetch_assoc($result);
    $ukm_name = $ukm['name'];
    $ukm_description = $ukm['description'];
    $ukm_email = $ukm['email'];
    $ukm_instagram = $ukm['instagram'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - U-COMM</title>
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
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUKM" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        UKM
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownUKM">
                        <a class="dropdown-item" href="#">Agama</a>
                        <a class="dropdown-item" href="#">Olahraga</a>
                        <a class="dropdown-item" href="#">Kemahasiswaan</a>
                        <a class="dropdown-item" href="#">Minat & Bakat</a>
                        <a class="dropdown-item" href="#">Seni</a>
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

    <div class="container mt-5">
        <?php if ($role == 'student'): ?>
            <div class="row">
                <div class="col-md-3">
                    <img src="uploaded_avatars/<?php echo $avatar; ?>" class="rounded-circle img-thumbnail" alt="Avatar" width="150" height="150">
                </div>
                <div class="col-md-9">
                    <h2><?php echo $name; ?></h2>
                    <p><strong>Username:</strong> <?php echo $username; ?></p>
                    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                    <p><strong>Jurusan:</strong> <?php echo $jurusan; ?></p>
                    <p><strong>Fakultas:</strong> <?php echo $fakultas; ?></p>
                    <p><strong>NIM:</strong> <?php echo $nim; ?></p>
                    <p><strong>Phone:</strong> <?php echo $phone; ?></p>
                </div>
            </div>

            <h3 class="mt-5">Wishlist Event</h3>
            <div class="row">
                <?php foreach ($wishlist_events as $event): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="<?php echo $event['header_image']; ?>" class="card-img-top" alt="Event Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $event['name']; ?></h5>
                                <p class="card-text">Range Oprec: <?php echo date('d/m/Y', strtotime($event['start_date'])); ?> - <?php echo date('d/m/Y', strtotime($event['end_date'])); ?></p>
                                <a href="daftar_event.php?event_id=<?php echo $event['id']; ?>" class="btn btn-success">Daftar Sekarang</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <h3 class="mt-5">History Event</h3>
            <div class="row">
                <?php foreach ($history_events as $event): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="<?php echo $event['header_image']; ?>" class="card-img-top" alt="Event Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $event['name']; ?></h5>
                                <p class="card-text">Range Oprec: <?php echo date('d/m/Y', strtotime($event['start_date'])); ?> - <?php echo date('d/m/Y', strtotime($event['end_date'])); ?></p>
                                <p class="card-text">Tanggal Pendaftaran: <?php echo date('d/m/Y', strtotime($event['registration_date'])); ?></p>
                                <p class="card-text">Status: <?php echo ucfirst($event['status']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($role == 'ukm'): ?>
            <div class="row">
                <div class="col-md-3">
                    <img src="uploaded_avatars/<?php echo $avatar; ?>" class="rounded-circle img-thumbnail" alt="Avatar" width="150" height="150">
                </div>
                <div class="col-md-9">
                    <h2><?php echo $ukm_name; ?></h2>
                    <p><strong>Username:</strong> <?php echo $username; ?></p>
                    <p><strong>Email:</strong> <?php echo $ukm_email; ?></p>
                    <p><strong>Instagram:</strong> <?php echo $ukm_instagram; ?></p>
                    <p><strong>Deskripsi:</strong> <?php echo $ukm_description; ?></p>
                </div>
            </div>
            <h3 class="mt-5">Ongoing Events</h3>
            <!-- Tampilkan ongoing events -->
            <h3 class="mt-5">Event History</h3>
                <div class="row">
                    <!-- Loop through the history events -->
            <?php foreach ($history_events as $event): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                        <img src="<?php echo $event['header_image']; ?>" class="card-img-top" alt="Event Image">
                    <div class="card-body">
                    <h5 class="card-title"><?php echo $event['name']; ?></h5>
                        <p class="card-text">Range Oprec: <?php echo date('d/m/Y', strtotime($event['start_date'])); ?> - <?php echo date('d/m/Y', strtotime($event['end_date'])); ?></p>
                        <p class="card-text">Tanggal Pendaftaran: <?php echo date('d/m/Y', strtotime($event['registration_date'])); ?></p>
                        <p class="card-text">Status: <?php echo ucfirst($event['status']); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
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