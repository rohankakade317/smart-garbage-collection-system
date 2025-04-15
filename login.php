<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Function to validate user credentials
    function validateCredentials($username, $password, $file) {
        $handle = fopen($file, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $fields = explode(",", $line);
                if (trim($fields[4]) === $username && trim($fields[5]) === $password) {
                    fclose($handle);
                    return true; // Credentials matched
                }
            }
            fclose($handle);
        } else {
            // Error opening the file
            return false;
        }
        return false; // Credentials not found
    }
    $adminCredentials = ['username' => 'admin', 'password' => 'admin123'];

    // Check role and credentials
    if ($role === 'driver' && validateCredentials($username, $password, 'admin/driver.csv')) {
        // Redirect to driver page
        header("Location: driver/driver_page.php");
        exit();
    } 
    elseif ($role === 'admin' && $username === $adminCredentials['username'] && $password === $adminCredentials['password']) {
        // Redirect to admin page
        header("Location:admin/admin_page.php");
        exit();
    }else {
        echo "Invalid username or password for driver!";
    }
}
?>
