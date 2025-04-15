<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $houseCode = trim($_POST['houseCode']);

    $existingFile = '../admin/house_data.csv';
    $found = false;

    if (file_exists($existingFile) && ($handle = fopen($existingFile, 'r')) !== false) {
        while (($row = fgetcsv($handle)) !== false) {
            if ($row[0] === $houseCode) {
                $found = true;
                echo "✅ House Found:<br>";
                echo "House Code: " . htmlspecialchars($row[0]) . "<br>";
                echo "Address: " . htmlspecialchars($row[1]) . "<br>";
                echo "Owner: " . htmlspecialchars($row[2]) . "<br>";
                break;
            }
        }
        fclose($handle);
    }

    if (!$found) {
        echo "❌ Error: House Code Not Registered.";
    }
} else {
    http_response_code(405);
    echo "❌ Error: Method Not Allowed.";
}
?>
