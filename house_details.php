<?php
// Define CSV file path
$csvFile = __DIR__ . '/house_data.csv';

// Handle Delete Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['houseOwner'])) {
    $houseOwner = trim($_POST['houseOwner']);
    $rows = [];
    $deleted = false;

    if (($handle = fopen($csvFile, 'r')) !== false) {
        while (($row = fgetcsv($handle)) !== false) {
            if (trim($row[0]) !== $houseOwner) {
                $rows[] = $row;
            } else {
                $deleted = true;
            }
        }
        fclose($handle);
    }

    if ($deleted) {
        if (($handle = fopen($csvFile, 'w')) !== false) {
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }
        echo "success";
    } else {
        echo "not found";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>House Details</title>

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
        max-width: 900px;
        margin: auto;
    }

    .table-container {
        background: #112240;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5);
    }

    .table th {
        background: #233554;
        color: #64ffda;
        text-transform: uppercase;
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
        <h1><i class="fas fa-home"></i> House Details</h1>

        <!-- Button Group -->
        <div class="btn-group">
            <button class="btn btn-back" onclick="window.location.href='admin_page.php'">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </button>

            <a href="C:/rohan317/htdocs/rohan/project 2/admin/reg_house.html" class="btn btn-add"><i class="fas fa-plus"></i> Add House</a>
        </div>

        <!-- Search Box -->
        <input type="text" id="searchBox" class="form-control" placeholder="Search by House Owner...">

        <!-- Table Container -->
        <div class="table-container mt-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-light" id="houseTable">
                    <thead>
                        <tr>
                            <th>House ID</th>
                            <th>Address</th>
                            <th>Owner Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (($handle = fopen($csvFile, 'r')) !== false) {
                            while (($row = fgetcsv($handle)) !== false) {
                                if (!empty(array_filter($row))) {
                                    echo '<tr>';
                                    foreach ($row as $value) {
                                        echo '<td>' . htmlspecialchars($value) . '</td>';
                                    }
                                    echo '<td><button class="btn btn-delete" onclick="deleteRow(this)"><i class="fas fa-trash"></i> Delete</button></td>';
                                    echo '</tr>';
                                }
                            }
                            fclose($handle);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function deleteRow(button) {
            let row = button.closest('tr');
            let houseOwner = row.cells[0].innerText;

            if (!confirm(`Are you sure you want to delete "${houseOwner}"?`)) return;

            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'houseOwner=' + encodeURIComponent(houseOwner)
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "success") {
                    row.remove();
                } else if (data.trim() === "not found") {
                    alert('House owner not found in records.');
                } else {
                    alert('Error deleting house!');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Search Box Filtering
        document.getElementById('searchBox').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#houseTable tbody tr');

            rows.forEach(row => {
                let houseOwner = row.cells[0].innerText.toLowerCase();
                row.style.display = houseOwner.includes(filter) ? '' : 'none';
            });
        });
    </script>

</body>
</html>
