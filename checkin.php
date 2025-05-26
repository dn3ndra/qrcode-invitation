<?php
include 'auth.php';
$mysqli = new mysqli("localhost", "root", "", "wedding_guestbook");

$id = $_GET['id'] ?? '';
if (!$id) {
    echo "QR Code tidak valid."; exit;
}

$result = $mysqli->query("SELECT * FROM guests WHERE id = '$id'");
if ($result->num_rows == 0) {
    echo "Tamu tidak ditemukan."; exit;
}

$tamu = $result->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $stmt = $mysqli->prepare("UPDATE guests SET status = ?, checkin_time = NOW() WHERE id = ?");
    $stmt->bind_param("ss", $status, $id);
    $stmt->execute();
    echo "✅ Kehadiran $status dicatat. Terima kasih!"; exit;
}
?>

<h3>Halo, <?= htmlspecialchars($tamu['name']) ?></h3>
<form method="post">
  <label><input type="radio" name="status" value="hadir" required> Hadir</label><br>
  <label><input type="radio" name="status" value="diwakilkan" required> Diwakilkan</label><br><br>
  <button type="submit">Konfirmasi Kehadiran</button>
</form>
