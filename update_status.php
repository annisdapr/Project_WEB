<?php
session_start();

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST
    $registrationId = $_POST['registration_id'];
    $status = $_POST['status'];

    // Query untuk update status
    $query_update = "UPDATE registrations SET status = '$status' WHERE id = $registrationId";
    $result_update = mysqli_query($koneksi, $query_update);

    if ($result_update) {
        $response = [
            'success' => true,
            'message' => 'Status berhasil diperbarui.'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Gagal memperbarui status.'
        ];
    }

    echo json_encode($response);
}
?>
