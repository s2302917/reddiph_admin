```php
<?php
/**
 * ============================================================
 * Hostinger Database Configuration
 * Database: u672637579_reddiph
 * User:     u672637579_reddiph
 * ============================================================
 */

// Local XAMPP / MySQL hostname
// Change this only if your local database runs on another host.
$db_host = "localhost";

// Database name
$db_name = "u672637579_reddiph";

// Database username
$db_user = "u672637579_reddiph";

// Database password
$db_pass = "Reddiphcapstone2";

// Character set
$charset = "utf8mb4";


/**
 * PDO Connection
 */
$dsn = "mysql:host={$db_host};dbname={$db_name};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];


try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);

} catch (PDOException $e) {

    // Do not expose database credentials/errors to users
    error_log("Database connection failed: " . $e->getMessage());

    die("Database connection failed. Please try again later.");
}
?>
```