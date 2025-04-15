<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CleanTrack: Smart Garbage Collection Monitor</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Global Styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            background: black;
        }

        /* Dashboard Container */
        .dashboard {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 90%;
            max-width: 1100px;
            background: rgba(255, 255, 255, 0.0);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 0 25px rgba(151, 18, 76, 5.9);
            padding: 30px;
            text-align: center;
            animation: fadeIn 1.5s ease-in-out;
        }

        h1 {
            font-size: 2.8rem;
            color: #fff;
            text-shadow: 0 0 20px rgba(255, 255, 255, 1);
            margin-bottom: 20px;
            animation: glowText 1.5s infinite alternate;
        }

        @keyframes glowText {
            from { text-shadow: 0 0 10px rgba(255, 255, 255, 0.8); }
            to { text-shadow: 0 0 20px rgba(255, 255, 255, 1); }
        }

        /* Statistics Container */
        .stats-container {
            display: flex;
            justify-content: space-around;
            width: 100%;
            margin-bottom: 20px;
        }

        .stats {
            flex: 1;
            padding: 20px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.3);
            /* box-shadow: 0 0 15px rgba(255, 255, 255, 0.3); */
            margin: 10px;
            transition: transform 0.3s ease-in-out;
        }

        .stats p {
            font-size: 1.4rem;
            font-weight: bold;
            /* text-shadow: 0 0 10px rgba(255, 255, 255, 0.8); */
            color: #fff;
        }

        .stats p strong {
            color: #ffeb3b;
        }

        .stats p i {
            color: #ffcc00;
        }

        /* Buttons */
        .actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

      /* Buttons */
.btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 200px;
    padding: 12px;
    margin: 10px;
    font-size: 1rem;
    font-weight: bold;
    border-radius: 8px;
    text-decoration: none;
    color: #fff;
    background: linear-gradient(to right, #8E2DE2, #FF416C);
    transition: all 0.3s ease-in-out;
    position: relative;
    overflow: hidden;
}

.btn:hover {
    transform: scale(1.1);
    background: linear-gradient(to right, #7A1FA2, #FF2A50);
}

        .btn i {
            margin-right: 10px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Responsive Styling */
@media (max-width: 768px) {
    .dashboard {
        width: 95%;
        padding: 20px;
    }
    
    h1 {
        font-size: 2rem;
    }
    
    .stats-container {
        flex-direction: column;
        align-items: center;
    }
    
    .stats {
        width: 90%;
        margin: 10px 0;
    }
    
    .actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .dashboard {
        width: 80%;
    }
    
    .stats-container {
        flex-wrap: wrap;
    }
    
    .stats {
        width: 45%;
    }
    
    .actions {
        justify-content: center;
    }
}

@media (min-width: 1025px) {
    .dashboard {
        width: 70%;
    }
}
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>CleanTrack: Smart Garbage Collection Monitor</h1>
        
        <div class="stats-container">
            <div class="stats">
                <p><i class="fas fa-home"></i> Total Houses: 
                    <strong>
                        <?php
                        function calculate_stats() {
                            $total_houses = count(file('house_data.csv'));
                            $total_scanned = count(file('../driver/scanned_house_data.csv'));
                            $remaining_houses = $total_houses - $total_scanned;
                            return array($total_houses, $total_scanned, $remaining_houses);
                        }

                        list($total_houses, $total_scanned, $remaining_houses) = calculate_stats();
                        echo $total_houses;
                        ?>
                    </strong>
                </p>
            </div>
            <div class="stats">
                <p><i class="fas fa-check-circle"></i> Scanned Houses: 
                    <strong><?php echo $total_scanned; ?></strong>
                </p>
            </div>
            <div class="stats">
                <p><i class="fas fa-exclamation-triangle"></i> Remaining Houses: 
                    <strong><?php echo $remaining_houses; ?></strong>
                </p>
            </div>
        </div>
        
        <div class="actions">
            <a href="reg_driver/reg_driver.html" class="btn"><i class="fas fa-user-plus"></i> Register Driver</a>
            <a href="reg_house/reg_house.html" class="btn"><i class="fas fa-home"></i> Register Household</a>
            <a href="driver_details.php" class="btn"><i class="fas fa-id-card"></i> Driver Details</a>
            <a href="house_details.php" class="btn"><i class="fas fa-list"></i> Household Details</a>
            <a href="scanned_house.php" class="btn"><i class="fas fa-database"></i> Scanned Households</a>
        </div>
    </div>
</body>
</html>
