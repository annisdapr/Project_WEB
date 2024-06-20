<?php
session_start();
include 'koneksi.php'; // Sertakan file koneksi database

// Ambil data event UKM yang sedang berlangsung
$query_events = "SELECT * FROM events WHERE NOW() BETWEEN start_date AND end_date";
$result_events = mysqli_query($koneksi, $query_events);
$events = mysqli_fetch_all($result_events, MYSQLI_ASSOC);


// Ambil data events yang di-highlight
$query_highlighted_events = "SELECT * FROM events WHERE highlight = 'ya'";
$result_highlighted_events = mysqli_query($koneksi, $query_highlighted_events);
$highlighted_events = mysqli_fetch_all($result_highlighted_events, MYSQLI_ASSOC);


// Periksa apakah pengguna sudah login
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Query untuk mengambil event yang sudah didaftarkan oleh pengguna
    $query_registered_events = "
        SELECT event_id
        FROM registrations
        WHERE user_id = '$user_id'
    ";
    $result_registered_events = mysqli_query($koneksi, $query_registered_events);
    $registered_events = mysqli_fetch_all($result_registered_events, MYSQLI_ASSOC);

    // Simpan id event yang sudah didaftarkan ke dalam array
    $registered_event_ids = array_column($registered_events, 'event_id');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>U-COMM - Homepage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
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
    <!-- Navbar -->
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

<!-- Caraousel -->
<!-- <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="image/banner1bismillah.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="image/banner2.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="image/banner3fixbgt.png" class="d-block w-100" alt="...">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div> -->
    <!-- Highlight Carrousel -->

    <!-- Highlight Carrousel -->
    <div id="highlightCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($highlighted_events as $index => $event): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="<?php echo $event['header_image']; ?>" class="d-block w-100" alt="Event Image">
                <div class="carousel-caption d-none d-md-block">
                    <h5><?php echo $event['name']; ?></h5>
                    <p><?php echo $event['deskripsi']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <a class="carousel-control-prev" href="#highlightCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#highlightCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div> 
    <!-- Ongoing Oprec Section -->
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>On Going</h2>
            <div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Filter
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Kepanitiaan</a>
                        <a class="dropdown-item" href="#">Anggota UKM</a>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sort
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Deadline Terdekat</a>
                        <a class="dropdown-item" href="#">Deadline Terjauh</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Oprec Cards -->
        <div class="row mt-4">
        <?php foreach ($events as $event): ?>
    <div class="col-md-4">
        <div class="card mb-4">
            <img src="<?php echo $event['header_image']; ?>" class="card-img-top" alt="Event Image">
            <div class="card-body">
                <h5 class="card-title"><?php echo $event['name']; ?></h5>
                <p class="card-text"><?php echo $event['deskripsi']; ?></p>
                <p class="card-text">Range Oprec: <?php echo date('d/m/Y', strtotime($event['start_date'])); ?> - <?php echo date('d/m/Y', strtotime($event['end_date'])); ?></p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (in_array($event['id'], $registered_event_ids)): ?>
                        <button class="btn btn-secondary" disabled>Already Registered</button>
                    <?php else: ?>
                        <button class="btn btn-primary add-to-wishlist" data-event-id="<?php echo $event['id']; ?>">Wishlist</button>
                        <a href="daftar_event.php?event_id=<?php echo $event['id']; ?>" class="btn btn-success">Daftar Sekarang</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Wishlist</a>
                    <a href="login.php" class="btn btn-success">Daftar Sekarang</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script>
    $(document).ready(function() {
    $('.add-to-wishlist').click(function() {
        var button = $(this);
        var eventId = button.data('event-id');
        
        // Pengecekan apakah event sudah terdaftar
        if (button.hasClass('btn-secondary')) {
            alert('Anda sudah mendaftar untuk event ini.');
            return false; // Batalkan operasi jika sudah terdaftar
        }
        
        $.post('add_to_wishlist.php', { event_id: eventId }, function(response) {
            alert(response.message);
            if (response.success) {
                button.removeClass('btn-primary').addClass('btn-secondary').text('In Wishlist');
            }
        }, 'json');
    });
});
    </script>
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
