<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login dan memiliki role ukm
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'ukm') {
    header("Location: login.php");
    exit();
}

// Ambil event_id dari parameter GET
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
} else {
    header("Location: ukm_dashboard.php");
    exit();
}

// Ambil data event dari database
$query_event = "SELECT * FROM events WHERE id = ?";
$stmt_event = $koneksi->prepare($query_event);
$stmt_event->bind_param('i', $event_id);
$stmt_event->execute();
$result_event = $stmt_event->get_result();
$event = $result_event->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
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

    // Debugging: Print values
    echo "Start Date: $start_date<br>";
    echo "End Date: $end_date<br>";

    // Periksa apakah tanggal dalam rentang yang valid
    if ($start_date === false || $end_date === false) {
        $error_message = "Format tanggal tidak valid.";
    } else {
        // Upload header gambar
        if ($_FILES['header_image']['name']) {
            $target_dir = "uploads/";
            $header_image = $target_dir . basename($_FILES["header_image"]["name"]);
            move_uploaded_file($_FILES["header_image"]["tmp_name"], $header_image);
        } else {
            $header_image = $event['header_image'];
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

        // Update data event ke database
        $query_update = "UPDATE events SET name = ?, type = ?, deskripsi = ?, start_date = ?, end_date = ?, total_participants = ?, header_image = ?, divisi = ?, requirements = ?, additional_requirements = ?, contact_person_name = ?, contact_person_id_line = ?, contact_person_phone = ? WHERE id = ?";
        $stmt_update = $koneksi->prepare($query_update);
        if ($stmt_update === false) {
            die('Prepare failed: ' . htmlspecialchars($koneksi->error));
        }
        $stmt_update->bind_param('ssssississsssi', $name, $type, $deskripsi, $start_date, $end_date, $total_participants, $header_image, $divisi, $requirements, $additional_requirements, $contact_person_name, $contact_person_id_line, $contact_person_phone, $event_id);

        // Debugging: Print the query
        echo "Query: $query_update<br>";
        echo "Values: $name, $type, $deskripsi, $start_date, $end_date, $total_participants, $header_image, $divisi, $requirements, $additional_requirements, $contact_person_name, $contact_person_id_line, $contact_person_phone, $event_id<br>";

        if ($stmt_update->execute()) {
            // Redirect ke halaman detail acara
            header("Location: lihat_event_ukm.php?id=$event_id");
            exit();
        } else {
            $error_message = "Gagal mengupdate acara: " . htmlspecialchars($stmt_update->error);
        }
    }
}

// Ambil divisi dari database
$query_divisi = "SELECT id, nama_divisi FROM divisi";
$result_divisi = mysqli_query($koneksi, $query_divisi);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Event</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label>Header Image</label>
                <input type="file" class="form-control" name="header_image">
                <img src="<?php echo $event['header_image']; ?>" class="img-fluid mt-2" alt="Current Image">
            </div>
            <div class="form-group">
                <label>Nama Kegiatan</label>
                <input type="text" class="form-control" name="name" value="<?php echo $event['name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Jenis Kegiatan</label>
                <select class="form-control" name="type" required>
                    <option value="kepanitiaan" <?php echo ($event['type'] == 'kepanitiaan') ? 'selected' : ''; ?>>Kepanitiaan</option>
                    <option value="anggota" <?php echo ($event['type'] == 'anggota') ? 'selected' : ''; ?>>Oprec Anggota</option>
                </select>
            </div>
            <div class="form-group">
                <label>Deskripsi Kegiatan</label>
                <textarea class="form-control" name="deskripsi" required><?php echo $event['deskripsi']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Tanggal Mulai</label>
                <input type="date" class="form-control" name="start_date" value="<?php echo $event['start_date']; ?>" required>
            </div>
            <div class="form-group">
                <label>Tanggal Selesai</label>
                <input type="date" class="form-control" name="end_date" value="<?php echo $event['end_date']; ?>" required>
            </div>
            <div class="form-group">
                <label>Jumlah Peserta</label>
                <input type="number" class="form-control" name="total_participants" value="<?php echo $event['total_participants']; ?>" required>
            </div>
            <div class="form-group">
                <label>Persyaratan</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="requirements[]" value="CV" <?php echo (strpos($event['requirements'], 'CV') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label">CV</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="requirements[]" value="KTM" <?php echo (strpos($event['requirements'], 'KTM') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label">KTM</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="requirements[]" value="KHS" <?php echo (strpos($event['requirements'], 'KHS') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label">KHS</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="requirements[]" value="Portofolio" <?php echo (strpos($event['requirements'], 'Portofolio') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label">Portofolio</label>
                </div>
            </div>
            <div class="form-group">
                <label>Persyaratan Tambahan</label>
                <textarea class="form-control" name="additional_requirements"><?php echo $event['additional_requirements']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Contact Person</label>
                <input type="text" class="form-control" name="contact_person_name" value="<?php echo $event['contact_person_name']; ?>" required>
            </div>
            <div class="form-group">
                <label>ID Line Contact Person</label>
                <input type="text" class="form-control" name="contact_person_id_line" value="<?php echo $event['contact_person_id_line']; ?>" required>
            </div>
            <div class="form-group">
                <label>No HP Contact Person</label>
                <input type="text" class="form-control" name="contact_person_phone" value="<?php echo $event['contact_person_phone']; ?>" required>
            </div>
            <div class="form-group">
                <label>Divisi</label>
                <?php while ($row_divisi = mysqli_fetch_assoc($result_divisi)): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="divisi[]" value="<?php echo $row_divisi['id']; ?>" <?php echo (($event['divisi'] & (1 << ($row_divisi['id'] - 1))) != 0) ? 'checked' : ''; ?>>
                        <label class="form-check-label"><?php echo $row_divisi['nama_divisi']; ?></label>
                    </div>
                <?php endwhile; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Event</button>
        </form>
    </div>
</body>
</html>
