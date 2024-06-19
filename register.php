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
</head>
<body>
    <h2>Register</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label>Username:</label>
            <input type="text" name="username" maxlength="50" required>
        </div>
        <div>
            <label>Nama:</label>
            <input type="text" name="name" maxlength="100" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" maxlength="100" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" maxlength="20" required>
        </div>
        <div>
            <button type="submit">Register</button>
        </div>
    </form>
    <?php
    if (!empty($error)) {
        echo "<p>$error</p>";
    }
    ?>
</body>
</html>
