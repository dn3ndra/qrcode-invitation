<?php
include 'auth.php';
require 'config.php';

function generateUuidV4() {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$error = '';
$success = '';
$generatedId = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        $error = 'Nama tamu harus diisi.';
    } else {
        $id = generateUuidV4();
        $stmt = $mysqli->prepare("INSERT INTO guests (id, name) VALUES (?, ?)");
        $stmt->bind_param("ss", $id, $name);
        if ($stmt->execute()) {
            $success = "Tamu berhasil ditambahkan.";
            $generatedId = $id;
        } else {
            $error = "Gagal menyimpan data tamu.";
        }
    }
}
?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Tamu dan Generate QR Code</title>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Montserrat:ital,wght@1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
      #qrcode-wrapper {
        display: flex;
        align-items: center;
        padding: 20px;
        background: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        width: 340px;  /* 9cm */
        height: 210px; /* 5.5cm */
      }

      #qrcode {
        flex-shrink: 0;
        margin-right: 20px;
      }

      #qr-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 12px;
        color: #333;
        max-width: 180px;
        word-wrap: break-word;
      }

      #qr-text .bold {
        font-weight: bold;
        font-size: 14px;
      }

      #qr-text .italic {
        font-style: italic;
        font-size: 10px;
      }
    </style>
</head>
<body>

<h2>Tambah Tamu Baru & Generate QR Code</h2>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
    <p><strong>Nama:</strong> <?= htmlspecialchars($name) ?></p>
    <p><strong>ID Tamu:</strong> <?= htmlspecialchars($generatedId) ?></p>

    <div id="qrcode-wrapper">
      <div id="qrcode"></div>
      <div id="qr-text">
        <p><span class="bold">Halo! <?= htmlspecialchars($name) ?></span><br> 
        <span class="italic">Tamu wajib menunjukkan kode QR ini sebagai pengganti buku tamu.<br>Jumlah tamu hanya berlaku untuk nama yang terdaftar.</span></p>
      </div>
    </div>
    <button id="downloadBtn">⬇️ Download QR Code</button>
    <p>Scan QR code ini untuk check-in:</p>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const checkinUrl = 'checkin.php?id=' + '<?= htmlspecialchars($generatedId) ?>';

        const qrContainer = document.getElementById('qrcode');
        new QRCode(qrContainer, {
          text: checkinUrl,
          width: 160,
          height: 160,
          correctLevel: QRCode.CorrectLevel.H
        });

        const downloadBtn = document.getElementById("downloadBtn");
        if (downloadBtn) {
          downloadBtn.addEventListener("click", function () {
            const wrapper = document.getElementById("qrcode-wrapper");
            if (!wrapper) return;

            html2canvas(wrapper, { useCORS: true, scale: 3 }).then(function (canvas) {
              const imageUrl = canvas.toDataURL("image/png");
              const guestNameRaw = '<?= htmlspecialchars($name) ?>';
              const guestNameSafe = guestNameRaw.replace(/[^a-z0-9]/gi, '_').toLowerCase();

              const link = document.createElement("a");
              link.href = imageUrl;
              link.download = `qrcode_${guestNameSafe}.png`;
              link.click();
            }).catch(function (error) {
              console.log('Error during html2canvas rendering:', error);
              alert("Terjadi kesalahan saat mencoba membuat gambar QR Code.");
            });
          });
        }
      });
    </script>

    <p><a href="add_guest.php">Tambah tamu lain</a></p>

<?php else: ?>

<form method="post">
    <label>Nama Tamu:<br />
        <input type="text" name="name" required style="width:300px;" />
    </label><br><br>
    <button type="submit">Tambah & Generate QR</button>
</form>

<?php endif; ?>

</body>
</html>
