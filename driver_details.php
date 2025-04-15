<?php
// Define the absolute path for driver.csv
$csvFile = __DIR__ . '/driver.csv';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Driver Details</title>

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

    <div class="container text-center">
        <h1 class="mt-4"><i class="fas fa-truck"></i> Driver Details</h1>
        
        <div class="btn-group">
            <a href="admin_page.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back</a>
            <a href="../reg_driver/reg_driver.html" class="btn btn-add"><i class="fas fa-plus"></i> Add Driver</a>
        </div>

        <div class="mb-3">
            <input type="text" id="searchBox" placeholder="Search driver by username...">
        </div>
        
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-hover  text-light" id="driverTable">
                    <thead>
                        <tr>
                            <th>Driver Name</th>
                            <th>Driver Contact</th>
                            <th>License No</th>
                            <th>Vehicle No</th>
                            <th>Username</th>
                            <th>Password</th>
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
                                        echo '<td class="text-center">' . htmlspecialchars($value) . '</td>';
                                    }
                                    echo '<td class="text-center"><button class="btn btn-delete" onclick="deleteRow(this)"><i class="fas fa-trash"></i> Delete</button></td>';
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
            let cells = row.getElementsByTagName('td');
            let driverName = cells[0].innerText;

            fetch('delete_driver.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'driverName=' + encodeURIComponent(driverName)
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "success") {
                    row.remove();
                } else {
                    alert('Error deleting driver!');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        document.getElementById('searchBox').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#driverTable tbody tr');
            
            rows.forEach(row => {
                let username = row.cells[4].innerText.toLowerCase();
                if (username.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

</body>
</html>
