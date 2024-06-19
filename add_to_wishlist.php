<?php
session_start();
include 'koneksi.php';

$response = array();

if (isset($_SESSION['user_id']) && isset($_POST['event_id'])) {
    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];

    // Cek apakah event sudah ada di wishlist
    $check_query = "SELECT * FROM wishlist WHERE user_id = ? AND event_id = ?";
    $stmt = $koneksi->prepare($check_query);
    $stmt->bind_param("ii", $user_id, $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['message'] = "Event sudah ada di wishlist Anda.";
        $response['success'] = false;
    } else {
        // Tambahkan event ke wishlist
        $insert_query = "INSERT INTO wishlist (user_id, event_id) VALUES (?, ?)";
        $stmt = $koneksi->prepare($insert_query);
        $stmt->bind_param("ii", $user_id, $event_id);

        if ($stmt->execute()) {
            $response['message'] = "Event berhasil ditambahkan ke wishlist Anda.";
            $response['success'] = true;
        } else {
            $response['message'] = "Gagal menambahkan event ke wishlist. Silakan coba lagi.";
            $response['success'] = false;
        }
    }
} else {
    $response['message'] = "Anda harus login untuk menambahkan event ke wishlist.";
    $response['success'] = false;
}

echo json_encode($response);
?>
