<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $users_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $deskripsi = $_POST['deskripsi'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $total_participants = $_POST['total_participants'];
    $additional_requirements = $_POST['additional_requirements'];
    $contact_person_name = $_POST['contact_person_name'];
    $contact_person_id_line = $_POST['contact_person_id_line'];
    $contact_person_phone = $_POST['contact_person_phone'];

    // Upload header gambar
    $header_image = '';
    if ($_FILES['header_image']['name']) {
        $target_dir = "uploads/";
        $header_image = $target_dir . basename($_FILES["header_image"]["name"]);
        move_uploaded_file($_FILES["header_image"]["tmp_name"], $header_image);
    }

    // Mendapatkan nilai bitmask dari divisi yang dipilih
    $divisi = 0;
    if (isset($_POST['divisi'])) {
        foreach ($_POST['divisi'] as $divisi_id) {
            $divisi |= (1 << ($divisi_id - 1));
        }
    }

    // Mendapatkan persyaratan yang dipilih
    $requirements = '';
    if (isset($_POST['requirements'])) {
        $requirements = implode(',', $_POST['requirements']);
    }
    
    // Query untuk mendapatkan ukm_id dari users_id dalam sesi
    $query_ukm_id = "SELECT id FROM ukm WHERE user_id = ?";
    $stmt_ukm_id = mysqli_prepare($koneksi, $query_ukm_id);
    mysqli_stmt_bind_param($stmt_ukm_id, 'i', $users_id);
    mysqli_stmt_execute($stmt_ukm_id);
    mysqli_stmt_store_result($stmt_ukm_id);

    // Memeriksa apakah ukm_id ditemukan
    if (mysqli_stmt_num_rows($stmt_ukm_id) > 0) {
        mysqli_stmt_bind_result($stmt_ukm_id, $ukm_id);
        mysqli_stmt_fetch($stmt_ukm_id);

        // Insert ke database
        $query = "INSERT INTO events (ukm_id, name, type, deskripsi, start_date, end_date, total_participants, header_image, divisi, requirements, additional_requirements, contact_person_name, contact_person_id_line, contact_person_phone)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, 'isssssisssssss', $ukm_id, $name, $type, $deskripsi, $start_date, $end_date, $total_participants, $header_image, $divisi, $requirements, $additional_requirements, $contact_person_name, $contact_person_id_line, $contact_person_phone);
        
        if (mysqli_stmt_execute($stmt)) {
            $event_id = mysqli_insert_id($koneksi);

            // Redirect ke halaman dashboard
            header("Location: ukm_dashboard.php");
            exit(); // Pastikan untuk keluar dari skrip setelah redirect
        } else {
            $error_message = "Gagal membuat acara: " . mysqli_error($koneksi);
        }
    } else {
        $error_message = "Error: ukm_id tidak ditemukan untuk users_id yang diberikan.";
    }

    mysqli_stmt_close($stmt_ukm_id);
    mysqli_close($koneksi);
}

// Ambil divisi dari database
$query_divisi = "SELECT id, name FROM divisi";
$result_divisi = mysqli_query($koneksi, $query_divisi);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Create Event</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label>Header Image</label>
                <input type="file" class="form-control" name="header_image">
            </div>
            <div class="form-group">
                <label>Nama Kegiatan</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label>Jenis Kegiatan</label>
                <select class="form-control" name="type" required>
                    <option value="kepanitiaan">Kepanitiaan</option>
                    <option value="anggota">Oprec Anggota</option>
                </select>
            </div>
            <div class="form-group">
                <label>Deskripsi Kegiatan</label>
                <textarea class="form-control" name="deskripsi" required></textarea>
            </div>
            <div class="form-group">
                <label>Tanggal Mulai</label>
                <input type="date" class="form-control" name="start_date" required>
            </div>
            <div class="form-group">
                <label>Tanggal Selesai</label>
                <input type="date" class="form-control" name="end_date" required>
            </div>
            <div class="form-group">
                <label>Total Partisipan</label>
                <input type="number" class="form-control" name="total_participants" required>
            </div>
            <div class="form-group">
                <label>Divisi yang Diperlukan</label>
                <?php while ($row = mysqli_fetch_assoc($result_divisi)): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="divisi[]" value="<?php echo $row['id']; ?>" id="divisi-<?php echo $row['id']; ?>">
                        <label class="form-check-label" for="divisi-<?php echo $row['id']; ?>">
                            <?php echo $row['name']; ?>
                        </label>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="form-group">
                <label>Berkas Persyaratan</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="requirements[]" value="KTM" id="requirement-ktm">
                    <label class="form-check-label" for="requirement-ktm">KTM</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="requirements[]" value="CV" id="requirement-cv">
                    <label class="form-check-label" for="requirement-cv">CV</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="requirements[]" value="KHS" id="requirement-khs">
                    <label class="form-check-label" for="requirement-khs">KHS</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="requirements[]" value="Portofolio" id="requirement-portofolio">
                    <label class="form-check-label" for="requirement-portofolio">Portofolio</label>
                </div>
            </div>
            <div class="form-group">
                <label>Persyaratan Tambahan</label>
                <textarea class="form-control" name="additional_requirements"></textarea>
            </div>
            <div class="form-group">
                <label>Contact Person</label>
                <input type="text" class="form-control" name="contact_person_name" placeholder="Nama" required>
                <input type="text" class="form-control mt-2" name="contact_person_id_line" placeholder="ID Line" required>
                <input type="text" class="form-control mt-2" name="contact_person_phone" placeholder="Nomor Telepon" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
