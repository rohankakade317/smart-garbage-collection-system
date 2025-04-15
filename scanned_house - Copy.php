<?php
// Define CSV file path
$csvFile = __DIR__ . '/../driver/scanned_house_data.csv';

// Handle Delete Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['houseID'])) {
    $houseID = trim($_POST['houseID']);
    $rows = [];

    if (file_exists($csvFile) && ($handle = fopen($csvFile, 'r')) !== false) {
        while (($row = fgetcsv($handle)) !== false) {
            if (trim($row[0]) !== $houseID) { // Keep all rows except the one being deleted
                $rows[] = $row;
            }
        }
        fclose($handle);
    }

    if (($handle = fopen($csvFile, 'w')) !== false) {
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }
    echo "success";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Scanned House Details</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- FontAwesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #0a192f;
        color: #fff;
        text-align: center;
        padding: 20px;
    }

    .container {
        max-width: 1000px;
        margin: auto;
    }

    .table-container {
        background: #112240;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5);
        overflow-x: auto;
    }

    .table th {
        background: #233554;
        color: #64ffda;
        text-transform: uppercase;
        text-align: center;
    }

    .table tbody tr:hover {
        background: #2a406e;
        cursor: pointer;
    }

    .btn-group {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .btn {
        padding: 12px 18px;
        font-weight: bold;
        border-radius: 8px;
        transition: 0.3s;
    }

    .btn-add {
        background: #007bff;
        color: #fff;
    }
    .btn-add:hover {
        background: #0056b3;
    }

    .btn-delete {
        background: #ff4757;
        color: #fff;
    }
    .btn-delete:hover {
        background: #e84118;
    }

    .btn-back {
        background: #17a2b8;
        color: #fff;
    }
    .btn-back:hover {
        background: #138496;
    }

    #searchBox {
        width: 100%;
        padding: 12px;
        border-radius: 5px;
        border: 1px solid #64ffda;
        margin-bottom: 15px;
        text-align: center;
        background: #112240;
        color: #64ffda;
    }
    #searchBox::placeholder {
        color: #64ffda;
        opacity: 0.7;
    }

    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h1 class="mt-4"><i class="fas fa-home"></i> Scanned House Details</h1>
    <div class="btn-group">
        <a href="admin_page.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
    <input type="text" id="searchBox" placeholder="🔍 Search scanned houses...">

    <div class="table-container">
        <table class="table table-hover text-light" id="scannedHouseTable">
            <thead>
                <tr>
                    <th>House ID</th>
                    <th>Scan Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (file_exists($csvFile) && ($handle = fopen($csvFile, 'r')) !== false) {
                    while (($row = fgetcsv($handle)) !== false) {
                        if (!empty(array_filter($row))) {
                            echo '<tr>';
                            foreach ($row as $value) {
                                echo '<td>' . htmlspecialchars($value) . '</td>';
                            }
                            echo '<td><button class="btn btn-delete" onclick="deleteRow(this, \'' . htmlspecialchars($row[0]) . '\')"><i class="fas fa-trash"></i> Delete</button></td>';
                            echo '</tr>';
                        }
                    }
                    fclose($handle);
                }
                ?>
            </tbody>
        </table>
        <div id="noHousesMessage" style="display:none; color:red; font-size:18px; font-weight:bold; margin-top:20px;">⚠️ No houses found!</div>
    </div>
</div>

<script>
    function deleteRow(button, houseID) {
        if (!confirm("Are you sure you want to delete this house?")) return;

        fetch(window.location.href, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'houseID=' + encodeURIComponent(houseID)
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                button.closest('tr').remove();
                checkEmptyTable();
            } else {
                alert('Error deleting house!');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function checkEmptyTable() {
        let table = document.querySelector("#scannedHouseTable tbody");
        document.getElementById("noHousesMessage").style.display = table.children.length === 0 ? "block" : "none";
    }

    document.addEventListener("DOMContentLoaded", checkEmptyTable);

    document.getElementById("searchBox").addEventListener("keyup", function () {
        let searchText = this.value.toLowerCase();
        let rows = document.querySelectorAll("#scannedHouseTable tbody tr");
        let hasMatch = false;

        rows.forEach(row => {
            let houseID = row.querySelector("td:first-child").innerText.toLowerCase();
            row.style.display = houseID.includes(searchText) ? "" : "none";
            if (houseID.includes(searchText)) hasMatch = true;
        });
        document.getElementById("noHousesMessage").style.display = hasMatch ? "none" : "block";
    });
</script>

</body>
</html>