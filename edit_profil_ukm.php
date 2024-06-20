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

// Proses form edit profil jika ada
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $instagram = $_POST['instagram'];
    $deskripsi = $_POST['deskripsi'];

    // Upload avatar baru jika ada
    if ($_FILES['avatar']['name']) {
        $avatar_name = $_FILES['avatar']['name'];
        $avatar_tmp_name = $_FILES['avatar']['tmp_name'];
        $avatar_size = $_FILES['avatar']['size'];
        $avatar_type = $_FILES['avatar']['type'];

        // Tentukan lokasi penyimpanan file avatar di server
        $target_dir = "uploads/foto_profil";
        $avatar_path = $target_dir . basename($avatar_name);

        // Cek tipe file yang diizinkan (di sini hanya contoh, pastikan sesuai kebutuhan Anda)
        $allowed_types = array('image/jpeg', 'image/png');
        if (!in_array($avatar_type, $allowed_types)) {
            $error_message = "Hanya file JPG, JPEG, atau PNG yang diizinkan.";
        } elseif ($avatar_size > 5000000) { // 5MB (sama juga, disesuaikan)
            $error_message = "Ukuran file terlalu besar. Maksimum 5 MB.";
        } elseif (!move_uploaded_file($avatar_tmp_name, $avatar_path)) {
            $error_message = "Gagal mengunggah file. Silakan coba lagi.";
        }

        // Update path avatar di database jika berhasil diunggah
        if (empty($error_message)) {
            // Hapus avatar lama jika ada
            if (file_exists($ukm['avatar'])) {
                unlink($ukm['avatar']);
            }

            // Query untuk update profil UKM dengan avatar baru
            $query_update = "UPDATE ukm SET name = ?, email = ?, instagram = ?, deskripsi = ?, avatar = ? WHERE user_id = ?";
            $stmt_update = $koneksi->prepare($query_update);
            $stmt_update->bind_param('sssssi', $name, $email, $instagram, $deskripsi, $avatar_path, $ukm_id);
        }
    } else {
        // Jika tidak ada file avatar yang diunggah, update profil tanpa avatar
        $query_update = "UPDATE ukm SET name = ?, email = ?, instagram = ?, deskripsi = ? WHERE user_id = ?";
        $stmt_update = $koneksi->prepare($query_update);
        $stmt_update->bind_param('ssssi', $name, $email, $instagram, $deskripsi, $ukm_id);
    }

    // Eksekusi update profil
    if ($stmt_update->execute()) {
        // Redirect kembali ke halaman dashboard
        header("Location: ukm_dashboard.php");
        exit();
    } else {
        $error_message = "Gagal mengupdate profil: " . $stmt_update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil UKM</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Edit Profil UKM</a>
        <!-- Navbar Content -->
    </nav>
    <div class="container mt-4">
        <h2>Edit Profil UKM</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label>Foto Avatar</label>
                <input type="file" class="form-control-file" name="avatar">
                <img src="<?php echo $ukm['avatar']; ?>" class="img-fluid mt-2" alt="Current Avatar">
            </div>
            <div class="form-group">
                <label>Nama UKM</label>
                <input type="text" class="form-control" name="name" value="<?php echo $ukm['name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo $ukm['email']; ?>" required>
            </div>
            <div class="form-group">
                <label>Instagram</label>
                <input type="text" class="form-control" name="instagram" value="<?php echo $ukm['instagram']; ?>">
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea class="form-control" name="deskripsi"><?php echo $ukm['deskripsi']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Profil</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
