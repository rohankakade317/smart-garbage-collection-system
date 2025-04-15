<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the scanned data from the POST request
    $scannedData = isset($_POST['scannedData']) ? $_POST['scannedData'] : '';

    // Validate the scanned data (you can add more validation if needed)
    if (!empty($scannedData)) {
        // Extract the house code from the scanned data
        preg_match('/House Code:\s*(\d+)/', $scannedData, $matches);
        $houseCode = isset($matches[1]) ? $matches[1] : '';

        // Check if the house code exists in the registered houses file and if it has been scanned today
        $registeredFile = '../admin/house_data.csv';
        $registered = false;
        $scannedToday = false;
        $today = date('Y-m-d');

        if (($handle = fopen($registeredFile, 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                if ($row[0] === $houseCode) {
                    // House code found in registered houses file
                    $registered = true;
                    break;
                }
            }
            fclose($handle);
        }

        $existingFile = 'scanned_house_data.csv';
        if (($handle = fopen($existingFile, 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                if ($row[0] === $houseCode && strpos($row[1], $today) !== false) {
                    // House code found and already scanned today
                    $scannedToday = true;
                    break;
                }
            }
            fclose($handle);
        }

        // Check if the house code is registered but not scanned today
        if ($registered && !$scannedToday) {
            // Save the scanned data
            $data = [$houseCode, date('Y-m-d H:i:s')];
            $handle = fopen($existingFile, 'a');
            fputcsv($handle, $data);
            fclose($handle);
            echo 'Scanned data saved successfully.';
        } else {
            // Display error messages based on conditions
            if (!$registered) {
                echo 'Error: House code not registered.';
            } elseif ($scannedToday) {
                echo 'Error: House code already scanned today.';
            }
        }
    } else {
        // Return an error message if no scanned data is received
        echo 'Error: No scanned data received.';
    }
} else {
    // Return an error message for invalid request method
    http_response_code(405);
    echo 'Error: Method Not Allowed.';
}
?>
