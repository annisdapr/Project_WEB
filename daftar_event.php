<?php
session_start();
include 'koneksi.php'; // Sertakan file koneksi database

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$event_id = $_GET['event_id'];

// Query untuk mengambil detail acara
$query_event = "SELECT * FROM events WHERE id = '$event_id'";
$result_event = mysqli_query($koneksi, $query_event);
$event = mysqli_fetch_assoc($result_event);

// Ambil divisi dari event
$divisi_event = $event['divisi'];

// Query untuk mengambil semua divisi
$query_divisi = "SELECT id, name FROM divisi";
$result_divisi = mysqli_query($koneksi, $query_divisi);

// Buat array divisi yang valid berdasarkan event
$divisi_valid = [];
while ($row = mysqli_fetch_assoc($result_divisi)) {
    if ($divisi_event & (1 << ($row['id'] - 1))) {
        $divisi_valid[] = $row;
    }
}

// Tangani pendaftaran
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $no_telepon = $_POST['no_telepon'];
    $fakultas = $_POST['fakultas'];
    $prodi = $_POST['prodi'];
    $divisi1 = $_POST['divisi1'];
    $divisi2 = $_POST['divisi2'];
    $alasan = $_POST['alasan'];

    // Upload file
    $cv = '';
    $khs = '';
    $portofolio = '';
    $target_dir = "uploads/";

    if (!empty($_FILES['cv']['name'])) {
        $cv = basename($_FILES['cv']['name']);
        move_uploaded_file($_FILES['cv']['tmp_name'], $target_dir . $cv);
    }

    if (!empty($_FILES['khs']['name'])) {
        $khs = basename($_FILES['khs']['name']);
        move_uploaded_file($_FILES['khs']['tmp_name'], $target_dir . $khs);
    }

    if (!empty($_FILES['portofolio']['name'])) {
        $portofolio = basename($_FILES['portofolio']['name']);
        move_uploaded_file($_FILES['portofolio']['tmp_name'], $target_dir . $portofolio);
    }

    // Masukkan data pendaftaran ke dalam database
    $query_register = "INSERT INTO registrations (user_id, event_id, nama, no_telepon, fakultas, prodi, divisi1, divisi2, alasan, cv, khs, portofolio, registration_date) VALUES ('$user_id', '$event_id', '$nama', '$no_telepon', '$fakultas', '$prodi', '$divisi1', '$divisi2', '$alasan', '$cv', '$khs', '$portofolio', NOW())";

    if (mysqli_query($koneksi, $query_register)) {
        // Hapus event dari wishlist setelah pendaftaran berhasil
        $query_remove_wishlist = "DELETE FROM wishlist WHERE user_id = '$user_id' AND event_id = '$event_id'";
        mysqli_query($koneksi, $query_remove_wishlist);
        $success_message = "Pendaftaran berhasil!";
    } else {
        $error_message = "Terjadi kesalahan. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Acara</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">U-COMM</a>
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
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
            <ul class="navbar-nav ml-2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="uploaded_avatars/<?php echo $_SESSION['avatar']; ?>" class="rounded-circle" alt="Avatar" width="30" height="30"> <?php echo $_SESSION['name']; ?>
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
        <h2>Form Pendaftaran Staff <?php echo $event['name']; ?></h2>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php else: ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama">Nama Panjang</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="no_telepon">No Telepon</label>
                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" required>
                </div>
                <div class="form-group">
                    <label for="fakultas">Fakultas</label>
                    <select class="form-control" id="fakultas" name="fakultas" required onchange="updateProdiOptions()">
                        <option value="">Pilih Fakultas</option>
                        <option value="FISIP">FISIP</option>
                        <option value="FEB">FEB</option>
                        <option value="FIK">FIK</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="prodi">Prodi</label>
                    <select class="form-control" id="prodi" name="prodi" required>
                        <option value="">Pilih Prodi</option>
                        <!-- Opsi Prodi akan diperbarui berdasarkan pilihan Fakultas -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="divisi1">Pilihan Divisi 1</label>
                    <select class="form-control" id="divisi1" name="divisi1" required onchange="updateDivisi2Options()">
                        <option value="">Pilih Divisi</option>
                        <?php foreach ($divisi_valid as $divisi): ?>
                            <option value="<?php echo $divisi['id']; ?>"><?php echo $divisi['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="divisi2">Pilihan Divisi 2</label>
                    <select class="form-control" id="divisi2" name="divisi2" required>
                        <option value="">Pilih Divisi</option>
                        <?php foreach ($divisi_valid as $divisi): ?>
                            <option value="<?php echo $divisi['id']; ?>"><?php echo $divisi['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="alasan">Alasan ingin mengikuti kepanitiaan</label>
                    <textarea class="form-control" id="alasan" name="alasan" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="cv">Upload CV</label>
                    <input type="file" class="form-control-file" id="cv" name="cv">
                </div>
                <div class="form-group">
                    <label for="khs">Upload KHS</label>
                    <input type="file" class="form-control-file" id="khs" name="khs">
                </div>
                <div class="form-group">
                    <label for="portofolio">Upload Portofolio</label>
                    <input type="file" class="form-control-file" id="portofolio" name="portofolio">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="agreement" required>
                    <label class="form-check-label" for="agreement">Dengan ini saya bersedia mengikuti segala proses recruitment yang ada</label>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        function updateProdiOptions() {
            var fakultas = document.getElementById('fakultas').value;
            var prodi = document.getElementById('prodi');
            prodi.innerHTML = '';

            var optionArray = [];
            if (fakultas === 'FISIP') {
                optionArray = ['Sosiologi', 'Ilmu Komunikasi', 'Ilmu Politik'];
            } else if (fakultas === 'FEB') {
                optionArray = ['Akuntansi', 'Manajemen', 'Ekonomi Pembangunan'];
            } else if (fakultas === 'FIK') {
                optionArray = ['Keperawatan', 'Kebidanan', 'Gizi'];
            }

            for (var option of optionArray) {
                var newOption = document.createElement('option');
                newOption.value = option;
                newOption.innerHTML = option;
                prodi.options.add(newOption);
            }
        }

        function updateDivisi2Options() {
            var divisi1 = document.getElementById('divisi1').value;
            var divisi2 = document.getElementById('divisi2');
            var divisiOptions = <?php echo json_encode($divisi_valid); ?>;

            divisi2.innerHTML = '';
            for (var i = 0; i < divisiOptions.length; i++) {
                if (divisiOptions[i].id != divisi1) {
                    var newOption = document.createElement('option');
                    newOption.value = divisiOptions[i].id;
                    newOption.innerHTML = divisiOptions[i].name;
                    divisi2.options.add(newOption);
                }
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
