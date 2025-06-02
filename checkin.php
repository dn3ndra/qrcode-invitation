<?php
include 'auth.php';
require 'config.php';

$id = $_GET['id'] ?? '';
if (!$id) {
    echo "QR Code tidak valid."; 
    exit;
}

// Check if guest exists
$stmt = $mysqli->prepare("SELECT * FROM guests WHERE id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Tamu tidak ditemukan."; 
    exit;
}

$tamu = $result->fetch_assoc();

// Check if already checked in
if ($tamu['kehadiran'] === 'hadir') {
    $message = "QR Code sudah pernah di-scan sebelumnya!";
    $status = "already_scanned";
    $guestName = htmlspecialchars($tamu['name']);
    $checkinTime = $tamu['checkin_time'] ? date('d/m/Y H:i', strtotime($tamu['checkin_time'])) : '-';
} else {
    // Automatically update kehadiran to 'hadir'
    $updateStmt = $mysqli->prepare("UPDATE guests SET kehadiran = 'hadir', checkin_time = NOW() WHERE id = ?");
    $updateStmt->bind_param("s", $id);
    
    if ($updateStmt->execute()) {
        $message = "Check-in berhasil!";
        $status = "success";
        $guestName = htmlspecialchars($tamu['name']);
        $checkinTime = date('d/m/Y H:i'); // Current time
    } else {
        $message = "Gagal melakukan check-in. Silakan coba lagi.";
        $status = "error";
        $guestName = htmlspecialchars($tamu['name']);
        $checkinTime = '-';
    }
    $updateStmt->close();
}

// Determine guest type (you can modify this logic based on your needs)
// For now, I'll assume all guests are "Regular" unless you have a specific field for VIP
$guestType = "Regular"; // You can add logic here to determine VIP vs Regular

$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Result</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .notification {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }
        
        .success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border: 2px solid #28a745;
            color: #155724;
        }
        
        .already-scanned {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 2px solid #ffc107;
            color: #856404;
        }
        
        .error {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            border: 2px solid #dc3545;
            color: #721c24;
        }
        
        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        .guest-name {
            font-size: 24px;
            font-weight: bold;
            margin: 15px 0;
        }
        
        .guest-details {
            margin: 20px 0;
            font-size: 16px;
        }
        
        .guest-type {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .type-regular {
            background: #e3f2fd;
            color: #1976d2;
            border: 1px solid #1976d2;
        }
        
        .type-vip {
            background: #fce4ec;
            color: #c2185b;
            border: 1px solid #c2185b;
        }
        
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background: #0056b3;
        }
        
        .time-info {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }
        
        @media (max-width: 480px) {
            .notification {
                margin: 20px;
                padding: 20px;
            }
            
            .guest-name {
                font-size: 20px;
            }
            
            .icon {
                font-size: 50px;
            }
        }
    </style>
</head>
<body>

<div class="notification <?= $status === 'success' ? 'success' : ($status === 'already_scanned' ? 'already-scanned' : 'error') ?>">
    <div class="icon">
        <?php if ($status === 'success'): ?>
            ✅
        <?php elseif ($status === 'already_scanned'): ?>
            ⚠️
        <?php else: ?>
            ❌
        <?php endif; ?>
    </div>
    
    <div class="guest-name"><?= $guestName ?></div>
    
    <div class="guest-type type-<?= strtolower($guestType) ?>">
        <?= $guestType ?>
    </div>
    
    <div class="guest-details">
        <strong><?= $message ?></strong>
    </div>
    
    <?php if ($status === 'success'): ?>
        <div class="time-info">
            Waktu check-in: <?= $checkinTime ?>
        </div>
    <?php elseif ($status === 'already_scanned'): ?>
        <div class="time-info">
            Sudah check-in pada: <?= $checkinTime ?>
        </div>
    <?php endif; ?>
    
    <button class="btn" onclick="goBackToScanner()">OK</button>
</div>

<script>
function goBackToScanner() {
    // Redirect back to scanner page
    window.location.href = 'scanner.php';
}

// Auto redirect after 5 seconds for success cases
<?php if ($status === 'success'): ?>
setTimeout(function() {
    goBackToScanner();
}, 5000);
<?php endif; ?>

// Show countdown for auto redirect
<?php if ($status === 'success'): ?>
let countdown = 5;
const updateCountdown = () => {
    if (countdown > 0) {
        document.querySelector('.btn').textContent = `OK (${countdown})`;
        countdown--;
        setTimeout(updateCountdown, 1000);
    } else {
        document.querySelector('.btn').textContent = 'OK';
    }
};
setTimeout(updateCountdown, 1000);
<?php endif; ?>
</script>

</body>
</html>