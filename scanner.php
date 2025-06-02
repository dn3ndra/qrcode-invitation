<?php 
include 'auth.php';
include 'navbar.php'; 

// Set your domain or base URL dynamically here
$baseUrl = 'http://localhost:3000'; // Change this to your domain if needed
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>Scan QR Tamu</title>
  <script src="https://unpkg.com/html5-qrcode"></script>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Reset and mobile-first approach */
    body {
        margin: 0;
        padding: 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: #f5f5f5;
        min-height: 100vh;
    }
    
    /* Main container - matches login page style */
    .scanner-container {
        max-width: 400px;
        margin: 20px auto;
        padding: 0 15px;
    }
    
    .scanner-card {
        background: #fff;
        padding: 30px 20px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        margin: 0;
    }
    
    .scanner-title {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
        font-weight: 600;
    }
    
    .scanner-instructions {
        font-size: 16px;
        color: #666;
        margin-bottom: 20px;
        line-height: 1.5;
        text-align: center;
        padding: 0;
    }
    
    /* QR Reader container - optimized for mobile */
    #reader { 
        width: 100% !important; 
        max-width: 100% !important;
        margin: 20px 0 !important;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        background: #000;
        min-height: 280px;
        display: block;
    }
    
    /* Override html5-qrcode default styles for mobile */
    #reader > div {
        border: none !important;
    }
    
    #reader video {
        width: 100% !important;
        height: auto !important;
        border-radius: 8px;
    }
    
    /* Scanner UI controls styling - match login form style */
    #reader select {
        width: 100% !important;
        padding: 16px !important;
        font-size: 16px !important;
        border: 2px solid #e1e5e9 !important;
        border-radius: 8px !important;
        background: #fff !important;
        margin: 10px 0 !important;
        min-height: 52px !important;
        transition: border-color 0.2s !important;
    }
    
    #reader select:focus {
        outline: none !important;
        border-color: #007bff !important;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1) !important;
    }
    
    #reader button {
        width: 100% !important;
        padding: 16px 24px !important;
        font-size: 18px !important;
        font-weight: 600 !important;
        background: #007bff !important;
        color: white !important;
        border: none !important;
        border-radius: 8px !important;
        min-height: 56px !important;
        margin: 10px 0 !important;
        cursor: pointer !important;
        transition: background-color 0.2s !important;
    }
    
    #reader button:hover {
        background: #0056b3 !important;
    }
    
    #reader button:active {
        transform: translateY(1px) !important;
    }
    
    /* File input styling - match form input style */
    #reader input[type="file"] {
        width: 100% !important;
        padding: 16px !important;
        font-size: 16px !important;
        border: 2px solid #e1e5e9 !important;
        border-radius: 8px !important;
        background: #fff !important;
        margin: 10px 0 !important;
        min-height: 52px !important;
        transition: border-color 0.2s !important;
    }
    
    #reader input[type="file"]:focus {
        outline: none !important;
        border-color: #007bff !important;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1) !important;
    }
    
    .status-message {
        font-size: 16px;
        font-weight: 500;
        color: #333;
        margin: 20px 0;
        padding: 16px;
        background: #e3f2fd;
        border-radius: 8px;
        border: 1px solid #bbdefb;
        text-align: center;
        line-height: 1.4;
        word-wrap: break-word;
    }
    
    .status-success {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    
    .status-error {
        background: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    
    .manual-input-link {
        display: block;
        width: 100%;
        margin-top: 20px;
        padding: 16px 24px;
        background: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 600;
        transition: background-color 0.2s;
        min-height: 56px;
        line-height: 1.2;
        text-align: center;
        box-sizing: border-box;
    }
    
    .manual-input-link:hover {
        background: #545b62;
        text-decoration: none;
        color: white;
    }
    
    .manual-input-link:active {
        transform: translateY(1px);
    }
    
    /* Camera permission message */
    .camera-permission {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 16px;
        border-radius: 8px;
        margin: 20px 0;
        font-size: 16px;
        line-height: 1.4;
        text-align: center;
    }
    
    /* Loading state */
    .scanner-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 60px;
        color: #666;
        font-size: 16px;
    }
    
    .loading-spinner {
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 10px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Small mobile devices */
    @media (max-width: 480px) {
        .scanner-container {
            margin: 10px auto;
            padding: 0 10px;
        }
        
        .scanner-card {
            padding: 20px 15px;
        }
        
        .scanner-title {
            font-size: 20px;
            margin-bottom: 15px;
        }
        
        .scanner-instructions {
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        #reader {
            min-height: 250px;
            margin: 15px 0 !important;
        }
        
        .status-message {
            font-size: 14px;
            margin: 15px 0;
            padding: 12px;
        }
        
        .manual-input-link {
            font-size: 16px;
            padding: 14px 20px;
            margin-top: 15px;
        }
    }
    
    /* Tablet and larger screens */
    @media (min-width: 768px) {
        .scanner-container {
            max-width: 500px;
            margin: 40px auto;
        }
        
        .scanner-card {
            padding: 40px 30px;
        }
        
        .scanner-title {
            font-size: 28px;
            margin-bottom: 25px;
        }
        
        #reader {
            max-width: 400px !important;
            margin: 25px auto !important;
            min-height: 320px;
        }
    }
    
    /* Large desktop styles */
    @media (min-width: 1024px) {
        .scanner-container {
            max-width: 600px;
        }
        
        .scanner-card {
            padding: 50px 40px;
        }
        
        .scanner-title {
            font-size: 32px;
        }
        
        #reader {
            max-width: 450px !important;
            min-height: 350px;
        }
    }
    
    /* Landscape mobile optimization */
    @media (max-height: 500px) and (orientation: landscape) {
        .scanner-container {
            margin: 5px auto;
            padding: 0 10px;
        }
        
        .scanner-card {
            padding: 15px;
        }
        
        .scanner-title {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .scanner-instructions {
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        #reader {
            min-height: 180px;
            margin: 10px 0 !important;
        }
        
        .status-message {
            margin: 10px 0;
            padding: 8px;
            font-size: 14px;
        }
        
        .manual-input-link {
            margin-top: 10px;
            padding: 12px 16px;
            font-size: 14px;
        }
    }
  </style>
</head>
<body>
  <div class="scanner-container">
    <div class="scanner-card">
      <h2 class="scanner-title">📷 Scan QR Tamu Undangan</h2>
      <div class="scanner-instructions">
        Arahkan kamera ke QR Code untuk melakukan check-in tamu
      </div>
      <div id="reader"></div>
      <div id="status" class="status-message">Silakan arahkan kamera ke QR Code.</div>
      <a href="dashboard.php" class="manual-input-link">
        ✏️ Input Manual Check-in
      </a>
    </div>
  </div>

  <script>
    const baseUrl = '<?= $baseUrl ?>'; // Inject the base URL from PHP into JavaScript

    function onScanSuccess(decodedText, decodedResult) {
      console.log("Decoded text: ", decodedText); // Log the decoded QR content for debugging

      // Update status with success styling
      const statusEl = document.getElementById("status");
      statusEl.className = "status-message status-success";
      statusEl.innerText = "✅ QR Terdeteksi: " + decodedText;
      
      // Validate that the decoded text starts with 'checkin.php?id=' and remove any extra spaces
      if (decodedText.trim().startsWith("checkin.php?id=")) {
        console.log("Redirecting to: ", baseUrl + '/' + decodedText); // Debug the redirection URL

        // Add loading state
        statusEl.innerHTML = '<div class="loading-spinner"></div>Memproses check-in...';
        
        // Stop the scanner and perform the redirection
        html5QrcodeScanner.clear().then(_ => {
          window.location.href = baseUrl + '/' + decodedText; // Redirect to the decoded URL with the base domain
        }).catch(error => {
          console.error("Gagal menghentikan scanner", error);
          statusEl.className = "status-message status-error";
          statusEl.innerText = "❌ Gagal memproses QR Code. Silakan coba lagi.";
        });
      } else {
        statusEl.className = "status-message status-error";
        statusEl.innerText = "❌ QR tidak valid! Format QR tidak sesuai.";
        
        // Reset scanner after 3 seconds
        setTimeout(() => {
          statusEl.className = "status-message";
          statusEl.innerText = "Silakan arahkan kamera ke QR Code.";
        }, 3000);
      }
    }

    function onScanFailure(error) {
      // Handle scan failure silently - don't show errors for normal scanning process
      console.log("Scan failed: ", error);
    }


    // Initialize the QR code scanner with the HTML5 QR code scanner
    const html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", 
      { 
        fps: 10, 
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0,
        showTorchButtonIfSupported: true,
        showZoomSliderIfSupported: true,
        defaultZoomValueIfSupported: 2
      },
      false
    );

    // Start scanning for QR codes
    html5QrcodeScanner.render(onScanSuccess, onScanFailure).then(() => {
      // Scanner started successfully
      document.getElementById("status").className = "status-message";
      document.getElementById("status").innerText = "Silakan arahkan kamera ke QR Code.";
    }).catch(error => {
      // Handle camera permission or other initialization errors
      document.getElementById("status").className = "status-message status-error";
      document.getElementById("status").innerHTML = "❌ Tidak dapat mengakses kamera.<br><small>Pastikan Anda memberikan izin kamera dan refresh halaman.</small>";
    });
  </script>
</body>
</html>