<?php
$dbname = 'kings_db';
$dbuser = 'root';
$dbpass = '';
$dbhost = '127.0.0.1';

echo "Testing connection to $dbname at $dbhost as $dbuser...\n";

$conn = new mysqli($dbhost, $dbuser, $dbpass);

if ($conn->connect_error) {
    die("Connection failed (no db): " . $conn->connect_error . "\n");
}
echo "Connected to MySQL server successfully.\n";

if (!$conn->select_db($dbname)) {
    echo "Database '$dbname' does not exist or access denied.\n";
    
    // List databases to see what's available
    $result = $conn->query("SHOW DATABASES");
    echo "Available databases:\n";
    while ($row = $result->fetch_assoc()) {
        echo " - " . $row['Database'] . "\n";
    }
} else {
    echo "Successfully selected database '$dbname'.\n";
}

$conn->close();
