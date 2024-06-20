<?php
session_start();

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include "koneksi.php";

// Mendapatkan daftar event
$query_events = "SELECT * FROM events";
$result_events = mysqli_query($koneksi, $query_events);
$events = mysqli_fetch_all($result_events, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Manage Events</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Highlight</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo $event['id']; ?></td>
                        <td><?php echo $event['name']; ?></td>
                        <td><?php echo $event['deskripsi']; ?></td>
                        <td><?php echo $event['start_date']; ?></td>
                        <td><?php echo $event['end_date']; ?></td>
                        <td><?php echo $event['highlight']; ?></td>
                        <td>
                            <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="highlight_event.php?id=<?php echo $event['id']; ?>" class="btn btn-info"><?php echo $event['highlight'] == 'ya' ? 'Remove Highlight' : 'Highlight'; ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
