<?php
include "koneksi.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role = 'student';

    if (empty($username) || empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($koneksi, $query);
        if (mysqli_num_rows($result) > 0) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO users (username, name, email, password, role) VALUES ('$username', '$name', '$email', '$hashed_password', '$role')";
            $result = mysqli_query($koneksi, $query);
            if ($result) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
       <h2>Register</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label>Username:</label>
            <input class="form-control"  type="text" name="username" maxlength="50" required>
        </div>
        <div class="form-group">
            <label>Nama:</label>
            <input class="form-control"  type="text" name="name" maxlength="100" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input class="form-control"  type="email" name="email" maxlength="100" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input class="form-control"  type="password" name="password" maxlength="20" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Register</button>
        </div>
    </form> 
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
    </div>
    
    <?php
    if (!empty($error)) {
        echo "<p>$error</p>";
    }
    ?>
</body>
</html>
