<?php
session_start();

// Cek apakah user sudah login dan memiliki role ukm
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'ukm') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Ambil user_id dari parameter GET
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Query untuk mengambil detail pendaftar
    $query_detail = "SELECT * FROM registrations WHERE user_id = $user_id";
    $result_detail = mysqli_query($koneksi, $query_detail);
    $detail = mysqli_fetch_assoc($result_detail);
} else {
    header("Location: ukm_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftar</title>
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
    <div class="container mt-4">
        <h2>Detail Pendaftar</h2>
        <table class="table">
            <tbody>
                <tr>
                    <th scope="row">Nama</th>
                    <td><?php echo $detail['nama']; ?></td>
                </tr>
                <tr>
                    <th scope="row">No Telepon</th>
                    <td><?php echo $detail['no_telepon']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Fakultas</th>
                    <td><?php echo $detail['fakultas']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Program Studi</th>
                    <td><?php echo $detail['prodi']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Divisi 1</th>
                    <td><?php echo $detail['divisi1']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Divisi 2</th>
                    <td><?php echo $detail['divisi2']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Alasan</th>
                    <td><?php echo $detail['alasan']; ?></td>
                </tr>
                <tr>
                    <th scope="row">CV</th>
                    <td><a href="<?php echo $detail['cv']; ?>" target="_blank">Download</a></td>
                </tr>
                <tr>
                    <th scope="row">KHS</th>
                    <td><a href="<?php echo $detail['khs']; ?>" target="_blank">Download</a></td>
                </tr>
                <tr>
                    <th scope="row">Portofolio</th>
                    <td><a href="<?php echo $detail['portofolio']; ?>" target="_blank">Download</a></td>
                </tr>
                <tr>
                    <th scope="row">Status</th>
                    <td><?php echo $detail['status']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Tanggal Pendaftaran</th>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($detail['registration_date'])); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
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
