<?php
session_start();

// Cek apakah user sudah login dan memiliki role ukm
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'ukm') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Ambil event_id dari parameter GET
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
} else {
    header("Location: ukm_dashboard.php");
    exit();
}

// Query untuk mengambil detail event
$query_event = "SELECT * FROM events WHERE id = $event_id";
$result_event = mysqli_query($koneksi, $query_event);
$event = mysqli_fetch_assoc($result_event);

// Query untuk mengambil data peserta event
$query_participants = "
    SELECT 
        registrations.id, 
        registrations.user_id, 
        div1.nama_divisi AS divisi1, 
        div2.nama_divisi AS divisi2, 
        registrations.status, 
        users.name AS user_name
    FROM registrations
    INNER JOIN users ON registrations.user_id = users.id
    LEFT JOIN divisi AS div1 ON registrations.divisi1 = div1.id
    LEFT JOIN divisi AS div2 ON registrations.divisi2 = div2.id
    WHERE registrations.event_id = $event_id
";
$result_participants = mysqli_query($koneksi, $query_participants);
$participants = mysqli_fetch_all($result_participants, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Detail</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Event Detail</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="ukm_dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <h2><?php echo $event['name']; ?></h2>
                <p>Type: <?php echo $event['type']; ?></p>
                <p>Deskripsi: <?php echo $event['deskripsi']; ?></p>
                <p>Start Date: <?php echo date('d/m/Y', strtotime($event['start_date'])); ?></p>
                <p>End Date: <?php echo date('d/m/Y', strtotime($event['end_date'])); ?></p>
                <p>Total Participants: <?php echo $event['total_participants']; ?></p>
                <p>Requirements: <?php echo $event['requirements']; ?></p>
                <p>Additional Requirements: <?php echo $event['additional_requirements']; ?></p>
                <p>Contact Person: <?php echo $event['contact_person_name']; ?></p>
                <p>ID Line: <?php echo $event['contact_person_id_line']; ?></p>
                <p>Phone: <?php echo $event['contact_person_phone']; ?></p>
                <p>Divisi: <?php echo $event['divisi']; ?></p>
            </div>
            <div class="col-md-4">
                <img src="<?php echo $event['header_image']; ?>" class="img-fluid" alt="Event Image">
            </div>
        </div>
        <div class="mt-4">
            <h3>Data Peserta</h3>
            <?php if (mysqli_num_rows($result_participants) > 0): ?>
                <!-- Tabel Data Peserta -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Divisi Pilihan 1</th>
            <th>Divisi Pilihan 2</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($participants as $participant): ?>
    <tr>
        <td class="view-detail" data-user-id="<?php echo $participant['user_id']; ?>">
            <?php echo $participant['user_name']; ?>
        </td>
        <td>
            <button class="btn btn-sm btn-info select-division" data-registration-id="<?php echo $participant['id']; ?>" data-division="<?php echo $participant['divisi1']; ?>">Pilih</button>
            <?php echo $participant['divisi1']; ?>
        </td>
        <td>
            <button class="btn btn-sm btn-info select-division" data-registration-id="<?php echo $participant['id']; ?>" data-division="<?php echo $participant['divisi2']; ?>">Pilih</button>
            <?php echo $participant['divisi2']; ?>
        </td>
        <td>
            <select class="form-control status-select" data-registration-id="<?php echo $participant['id']; ?>">
                <option value="Pending" <?php echo ($participant['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Lolos" <?php echo ($participant['status'] == 'Lolos') ? 'selected' : ''; ?>>Lolos</option>
                <option value="Tidak Lolos" <?php echo ($participant['status'] == 'Tidak Lolos') ? 'selected' : ''; ?>>Tidak Lolos</option>
            </select>
        </td>
        <td>
            <button class="btn btn-primary save-status" data-registration-id="<?php echo $participant['id']; ?>">Save</button>
        </td>
    </tr>
<?php endforeach; ?>

    </tbody>
</table>

                <button class="btn btn-success finish-event" data-event-id="<?php echo $event_id; ?>">Selesai</button>
            <?php else: ?>
                <div class="alert alert-info">Belum ada peserta yang mendaftar untuk event ini.</div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
$(document).ready(function() {
    // Ketika tombol pilihan divisi dipilih
    $('.select-division').click(function() {
        var button = $(this);
        var registrationId = button.data('registration-id');
        var division = button.data('division');

        $.post('select_division.php', { registration_id: registrationId, division: division }, function(response) {
            alert(response.message);
        }, 'json');
    });

    // Ketika status dipilih dari dropdown
    $('.status-select').change(function() {
        var select = $(this);
        var registrationId = select.data('registration-id');
        var status = select.val();

        $.post('update_status.php', { registration_id: registrationId, status: status }, function(response) {
            alert(response.message);
        }, 'json');
    });

    // Ketika tombol simpan status dipilih
    $('.save-status').click(function() {
        var button = $(this);
        var registrationId = button.data('registration-id');
        var statusSelect = button.closest('tr').find('.status-select');
        var status = statusSelect.val();

        $.post('update_status.php', { registration_id: registrationId, status: status }, function(response) {
            alert(response.message);
        }, 'json');
    });

    // Ketika nama pendaftar diklik untuk melihat detail
    $('.view-detail').click(function() {
        var userId = $(this).data('user-id');

        // Lakukan pengalihan halaman atau tampilkan informasi detail pendaftar
        window.location.href = 'detail_pendaftar.php?user_id=' + userId;
    });
});
</script>

</body>
</html>
