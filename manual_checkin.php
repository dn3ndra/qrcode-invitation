<?php
include 'auth.php';
include 'navbar.php';
$mysqli = new mysqli("localhost", "root", "", "wedding_guestbook");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $instansi = trim($_POST['instansi'] ?? '');
    
    if ($name) {
        $full_name = $instansi ? "$name – $instansi" : $name;
        $stmt = $mysqli->prepare("INSERT INTO manual_guests (name) VALUES (?)");
        $stmt->bind_param("s", $full_name);
        $stmt->execute();
        echo "<p>✅ Tamu manual '$full_name' berhasil dicatat.</p>";
        echo "<a href='manual_checkin.php'>← Kembali</a>";
        exit;
    } else {
        echo "<p>⚠️ Nama wajib diisi.</p>";
    }
}
?>

<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>

<h3>✍️ Input Tamu Manual</h3>
<form method="post">
  <label>Nama Tamu:<br><input type="text" name="name" required></label><br><br>
  <label>Instansi/Asal (opsional):<br><input type="text" name="instansi"></label><br><br>
  <button type="submit">Catat Kehadiran</button>
</form>
