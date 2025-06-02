<?php
include 'auth.php';
require 'config.php';

// Handle search functionality
$searchTerm = '';
$whereClause = '';
$searchParams = [];

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = trim($_GET['search']);
    $whereClause = "WHERE name LIKE ?";
    $searchParams = ['%' . $searchTerm . '%'];
}

// Handle manual attendance update
$updateMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_attendance'])) {
    $guestId = $_POST['guest_id'];
    $newStatus = $_POST['status'];
    
    // Validate status
    if (in_array($newStatus, ['hadir', 'diwakilkan', 'belum_hadir'])) {
        if ($newStatus === 'belum_hadir') {
            // Reset attendance - clear both kehadiran and checkin_time
            $stmt = $mysqli->prepare("UPDATE guests SET kehadiran = NULL, checkin_time = NULL WHERE id = ?");
            $stmt->bind_param("s", $guestId);
        } else {
            // Set attendance - update kehadiran column and set checkin_time
            $stmt = $mysqli->prepare("UPDATE guests SET kehadiran = ?, checkin_time = NOW() WHERE id = ?");
            $stmt->bind_param("ss", $newStatus, $guestId);
        }
        
        if ($stmt->execute()) {
            $updateMessage = "✅ Status kehadiran berhasil diupdate!";
        } else {
            $updateMessage = "❌ Gagal mengupdate status kehadiran.";
        }
        $stmt->close();
    }
}

// Build query with search
if ($whereClause) {
    $stmt = $mysqli->prepare("SELECT * FROM guests $whereClause ORDER BY checkin_time DESC, name ASC");
    $stmt->bind_param("s", $searchParams[0]);
    $stmt->execute();
    $guests = $stmt->get_result();
} else {
    $guests = $mysqli->query("SELECT * FROM guests ORDER BY checkin_time DESC, name ASC");
}

// Get manual guests with search
if ($whereClause) {
    $stmt = $mysqli->prepare("SELECT * FROM manual_guests $whereClause ORDER BY checkin_time DESC");
    $stmt->bind_param("s", $searchParams[0]);
    $stmt->execute();
    $manuals = $stmt->get_result();
} else {
    $manuals = $mysqli->query("SELECT * FROM manual_guests ORDER BY checkin_time DESC");
}

// Calculate statistics - using kehadiran column instead of status
$total = $mysqli->query("SELECT COUNT(*) FROM guests")->fetch_row()[0];
$hadir = $mysqli->query("SELECT COUNT(*) FROM guests WHERE kehadiran = 'hadir'")->fetch_row()[0];
$diwakilkan = $mysqli->query("SELECT COUNT(*) FROM guests WHERE kehadiran = 'diwakilkan'")->fetch_row()[0];
$manual = $mysqli->query("SELECT COUNT(*) FROM manual_guests")->fetch_row()[0];
$belum_hadir = $total - $hadir - $diwakilkan;
?>

<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .search-container {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .stats-container {
            display: flex;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .stat-card {
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-width: 120px;
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .update-form {
            display: inline-block;
            margin: 0;
            padding: 0;
        }
        .update-form select {
            padding: 4px;
            margin: 0 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 12px;
        }
        .update-form button {
            padding: 4px 8px;
            margin: 0;
            font-size: 12px;
            width: auto;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-hadir { background: #d4edda; color: #155724; }
        .status-diwakilkan { background: #fff3cd; color: #856404; }
        .status-belum { background: #f8d7da; color: #721c24; }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .search-results {
            font-style: italic;
            color: #666;
            margin: 10px 0;
        }
        @media (max-width: 768px) {
            .stats-container {
                flex-direction: column;
            }
            table {
                font-size: 12px;
            }
            .update-form select,
            .update-form button {
                font-size: 10px;
                padding: 2px 4px;
            }
            table th, table td {
                padding: 4px 6px;
            }
        }
    </style>
</head>
<body>

<h2>📊 Dashboard Kehadiran</h2>

<?php if ($updateMessage): ?>
    <div class="message <?= strpos($updateMessage, '✅') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($updateMessage) ?>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-number"><?= $total ?></div>
        <div class="stat-label">Total Undangan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $hadir ?></div>
        <div class="stat-label">Hadir</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $diwakilkan ?></div>
        <div class="stat-label">Diwakilkan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $belum_hadir ?></div>
        <div class="stat-label">Belum Hadir</div>
    </div>
    <!-- <div class="stat-card">
        <div class="stat-number"><?= $manual ?></div>
        <div class="stat-label">Manual</div>
    </div> -->
</div>

<!-- Search Form -->
<div class="search-container">
    <h3>🔍 Cari Tamu</h3>
    <form method="GET" style="margin: 0; padding: 0; background: none; border: none; max-width: none;">
        <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" 
               placeholder="Masukkan nama tamu..." style="width: 300px; display: inline-block;">
        <button type="submit" style="width: auto; margin-left: 10px;">Cari</button>
        <?php if ($searchTerm): ?>
            <a href="dashboard.php" style="margin-left: 10px; text-decoration: none;">
                <button type="button" style="width: auto; background: #6c757d;">Reset</button>
            </a>
        <?php endif; ?>
    </form>
</div>

<?php if ($searchTerm): ?>
    <div class="search-results">
        Hasil pencarian untuk: "<strong><?= htmlspecialchars($searchTerm) ?></strong>"
    </div>
<?php endif; ?>

<h3>📋 Tamu QR Code</h3>
<div style="overflow-x: auto;">
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Status QR</th>
                <th>Kehadiran Manual</th>
                <th>Waktu Check-in</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($guests->num_rows > 0): ?>
                <?php while ($row = $guests->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>
                            <?php if ($row['status']): ?>
                                <span class="status-badge status-<?= $row['status'] === 'hadir' ? 'hadir' : 'diwakilkan' ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-belum">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['kehadiran']): ?>
                                <span class="status-badge status-<?= $row['kehadiran'] === 'hadir' ? 'hadir' : 'diwakilkan' ?>">
                                    <?= ucfirst($row['kehadiran']) ?>
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-belum">Belum Hadir</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $row['checkin_time'] ? date('d/m/Y H:i', strtotime($row['checkin_time'])) : '-' ?></td>
                        <td>
                            <form method="POST" class="update-form">
                                <input type="hidden" name="guest_id" value="<?= htmlspecialchars($row['id']) ?>">
                                <select name="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="hadir" <?= $row['kehadiran'] === 'hadir' ? 'selected' : '' ?>>Hadir</option>
                                    <option value="diwakilkan" <?= $row['kehadiran'] === 'diwakilkan' ? 'selected' : '' ?>>Diwakilkan</option>
                                    <option value="belum_hadir">Reset (Belum Hadir)</option>
                                </select>
                                <button type="submit" name="update_attendance">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; font-style: italic; color: #666;">
                        <?= $searchTerm ? 'Tidak ada tamu yang ditemukan dengan kata kunci tersebut.' : 'Belum ada data tamu.' ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- <h3>📝 Tamu Manual</h3>
<div style="overflow-x: auto;">
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Waktu Check-in</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($manuals->num_rows > 0): ?>
                <?php while ($row = $manuals->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['checkin_time'] ? date('d/m/Y H:i', strtotime($row['checkin_time'])) : '-' ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" style="text-align: center; font-style: italic; color: #666;">
                        <?= $searchTerm ? 'Tidak ada tamu manual yang ditemukan.' : 'Belum ada data tamu manual.' ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div> -->

<script>
// Auto-refresh page every 30 seconds if no search is active
<?php if (!$searchTerm): ?>
setTimeout(function() {
    if (!document.querySelector('select:focus') && !document.querySelector('input:focus')) {
        location.reload();
    }
}, 30000);
<?php endif; ?>

// Confirm before updating attendance
document.querySelectorAll('.update-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        const select = form.querySelector('select');
        const guestName = form.closest('tr').querySelector('td:first-child').textContent;
        const newStatus = select.options[select.selectedIndex].text;
        
        if (!confirm(`Apakah Anda yakin ingin mengubah status kehadiran "${guestName}" menjadi "${newStatus}"?`)) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>