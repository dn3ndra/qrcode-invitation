<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "wedding_guestbook");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST["username"];
    $pass = $_POST["password"];

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row["password"])) {
            $_SESSION["loggedin"] = true;
            header("Location: scanner.php");
            exit;
        }
    }
    $error = "Login gagal!";
}
?>

<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>

<h2>🔐 Login Panitia</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
  Username: <input name="username" required><br><br>
  Password: <input name="password" type="password" required><br><br>
  <button type="submit">Login</button>
</form>
