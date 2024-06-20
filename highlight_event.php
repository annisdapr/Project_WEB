<?php
session_start();

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include "koneksi.php";

$id = $_GET['id'];
$query_event = "SELECT highlight FROM events WHERE id='$id'";
$result_event = mysqli_query($koneksi, $query_event);
$event = mysqli_fetch_assoc($result_event);

$new_highlight_status = $event['highlight'] == 'ya' ? 'tidak' : 'ya';

$query_update = "UPDATE events SET highlight='$new_highlight_status' WHERE id='$id'";
mysqli_query($koneksi, $query_update);

header("Location: admin_manage_events.php");
exit();
