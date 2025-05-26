<?php
include 'auth.php';
$mysqli = new mysqli("localhost", "root", "", "wedding_guestbook");

// Ambil data dari tabel guests
$guests = $mysqli->query("SELECT * FROM guests ORDER BY checkin_time DESC");
$manuals = $mysqli->query("SELECT * FROM manual_guests ORDER BY checkin_time DESC");

// Hitung statistik
$total = $mysqli->query("SELECT COUNT(*) FROM guests")->fetch_row()[0];
$hadir = $mysqli->query("SELECT COUNT(*) FROM guests WHERE status = 'hadir'")->fetch_row()[0];
$diwakilkan = $mysqli->query("SELECT COUNT(*) FROM guests WHERE status = 'diwakilkan'")->fetch_row()[0];
$manual = $mysqli->query("SELECT COUNT(*) FROM manual_guests")->fetch_row()[0];
?>

<?php include 'navbar.php'; ?>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>


<h2>📊 Dashboard Kehadiran</h2>
<p>
Total Undangan: <strong><?= $total ?></strong> |
Hadir: <strong><?= $hadir ?></strong> |
Diwakilkan: <strong><?= $diwakilkan ?></strong> |
Manual: <strong><?= $manual ?></strong>
</p>

<h3>📋 Tamu QR Code</h3>
<table border="1" cellpadding="5">
  <tr><th>Nama</th><th>Status</th><th>Waktu Check-in</th></tr>
  <?php while ($row = $guests->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= $row['status'] ?? '-' ?></td>
      <td><?= $row['checkin_time'] ?? '-' ?></td>
    </tr>
  <?php endwhile; ?>
</table>

<h3>📝 Tamu Manual</h3>
<table border="1" cellpadding="5">
  <tr><th>Nama</th><th>Waktu Check-in</th></tr>
  <?php while ($row = $manuals->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= $row['checkin_time'] ?? '-' ?></td>
    </tr>
  <?php endwhile; ?>
</table>
