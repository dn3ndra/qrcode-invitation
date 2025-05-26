<?php include 'auth.php'; ?>
<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Scan QR Tamu</title>
  <script src="https://unpkg.com/html5-qrcode"></script>
  <link rel="stylesheet" href="style.css">
  <style>
    body { font-family: sans-serif; text-align: center; margin: 0; padding: 1rem; }
    #reader { width: 100%; max-width: 400px; margin: auto; }
  </style>
</head>
<body>
  <h2>📷 Scan QR Tamu Undangan</h2>
  <div id="reader"></div>
  <p id="status">Silakan arahkan kamera ke QR Code.</p>

  <script>
    function onScanSuccess(decodedText, decodedResult) {
      document.getElementById("status").innerText = "✅ QR Terdeteksi: " + decodedText;
      
      // Hentikan kamera agar tidak terus scan
      html5QrcodeScanner.clear().then(_ => {
        // Redirect otomatis ke checkin
        window.location.href = decodedText;
      }).catch(error => {
        console.error("Gagal menghentikan scanner", error);
      });
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", 
      { fps: 10, qrbox: 250 },
      false
    );

    html5QrcodeScanner.render(onScanSuccess);
  </script>
</body>
</html>
