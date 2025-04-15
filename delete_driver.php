<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $driverName = $_POST['driverName'] ?? '';

    if (!empty($driverName)) {
        $csvFile = 'driver.csv';
        $tempFile = 'temp_driver.csv';

        $inputFile = fopen($csvFile, 'r');
        $outputFile = fopen($tempFile, 'w');

        while (($row = fgetcsv($inputFile)) !== false) {
            if ($row[0] !== $driverName) { // Compare the driver name
                fputcsv($outputFile, $row); // Write to temp file if it doesn't match
            }
        }

        fclose($inputFile);
        fclose($outputFile);

        // Replace original CSV file with the updated one
        if (rename($tempFile, $csvFile)) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }
}
?>
