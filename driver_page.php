<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Code Scanner with Camera Permission</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- FontAwesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- jQuery & Instascan -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<style>
    /* Global Styling */
    body {
        font-family: 'Poppins', sans-serif;
        background: #121212;
        color: #fff;
        margin: 0;
        padding: 20px;
        text-align: center;
    }

    /* Scanner Container */
    .scanner-container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        border-radius: 15px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
        text-align: center;
    }

    video {
        width: 100%;
        height: auto;
        border-radius: 10px;
        border: 3px solid #28a745;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.6);
    }

    /* Scan Result */
    #scan-result {
        margin-top: 15px;
        padding: 15px;
        border-radius: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: #0f0;
        font-size: 18px;
        font-weight: bold;
        animation: fadeIn 0.5s ease-in-out;
    }

    /* Buttons */
    .btn-custom {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        margin: 10px;
    }

    .btn-reset {
        background: #ff512f;
        color: white;
    }

    .btn-reset:hover {
        background: #e63946;
    }

    .btn-submit {
        background: #28a745;
        color: white;
    }

    .btn-submit:hover {
        background: #218838;
    }

    .btn-permission {
        background: #ff9f1c;
        color: white;
    }

    .btn-permission:hover {
        background: #e68a00;
    }

    /* Centering buttons */
    .btn-group {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 15px;
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

</style>
</head>
<body>

    <h1><i class="fas fa-qrcode"></i> QR Code Scanner</h1>

    <div class="scanner-container">
        <video id="preview"></video>
        <div id="scan-result">Waiting for scan...</div>

        <button class="btn-custom btn-permission" id="permission-btn" onclick="requestCameraPermission()">
            <i class="fas fa-video"></i> Grant Camera Access
        </button>
        
        <div class="btn-group" id="action-buttons" style="display: none;">
            <button class="btn-custom btn-reset" onclick="resetScan()">
                <i class="fas fa-sync"></i> Reset
            </button>

            <button class="btn-custom btn-submit" onclick="submitScannedData()">
                <i class="fas fa-save"></i> Submit
            </button>
        </div>
    </div>

    <script>
        let scanner;
        let cameraAccessGranted = false;

        function requestCameraPermission() {
            navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                cameraAccessGranted = true;
                document.getElementById('permission-btn').style.display = 'none'; // Hide permission button
                document.getElementById('action-buttons').style.display = 'flex'; // Show reset & submit buttons
                startScanner(); // Start scanner after permission is granted
            })
            .catch(function(error) {
                document.getElementById('scan-result').innerHTML = 
                '<i class="fas fa-times-circle"></i> Camera access denied! Please allow camera permissions.';
            });
        }

        function startScanner() {
            if (!cameraAccessGranted) {
                document.getElementById('scan-result').innerHTML = 
                '<i class="fas fa-exclamation-triangle"></i> Please grant camera access first!';
                return;
            }

            scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

            scanner.addListener('scan', function(content) {
                document.getElementById('scan-result').innerHTML = 
                '<i class="fas fa-check-circle"></i> Scanned: ' + content;

                $.ajax({
                    type: 'POST',
                    url: 'validate.php',
                    data: { houseCode: content },
                    success: function(response) {
                        document.getElementById('scan-result').innerHTML = 
                        '<i class="fas fa-info-circle"></i> ' + response;
                    },
                    error: function() {
                        document.getElementById('scan-result').innerHTML = 
                        '<i class="fas fa-exclamation-triangle"></i> Error validating QR!';
                    }
                });
            });

            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]); // Use first camera
                } else {
                    document.getElementById('scan-result').innerHTML = 
                    '<i class="fas fa-times-circle"></i> No camera found!';
                }
            });
        }

        function resetScan() {
            document.getElementById('scan-result').innerHTML = 'Waiting for scan...';
        }

        function submitScannedData() {
            let scannedData = document.getElementById('scan-result').textContent;
            if (scannedData.trim() !== 'Waiting for scan...') {
                $.ajax({
                    type: 'POST',
                    url: 'save_data.php',
                    data: { scannedData: scannedData },
                    success: function(response) {
                        alert(response);
                    }
                });
            } else {
                alert('No scanned data to submit.');
            }
        }
    </script>

</body>
</html>
