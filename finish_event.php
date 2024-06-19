<?php
session_start();

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST
    $eventId = $_POST['event_id'];

    // Query untuk menyelesaikan event
    $query_finish = "UPDATE events SET status = 'Selesai' WHERE id = $eventId";
    $result_finish = mysqli_query($koneksi, $query_finish);

    if ($result_finish) {
        $response = [
            'success' => true,
            'message' => 'Event berhasil ditandai sebagai selesai.'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Gagal menandai event sebagai selesai.'
        ];
    }

    echo json_encode($response);
}
?>
