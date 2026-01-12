<?php 
// 1. Define database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Default username for XAMPP is 'root'
define('DB_PASS', '');          // Default password for XAMPP is empty
define('DB_NAME', 'carrental'); // The database name we created earlier

// 2. Establish database connection (Using PDO for better security)
try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} 
catch (PDOException $e) {
    // If connection fails, stop script and show error
    exit("Error: " . $e->getMessage());
}
?>