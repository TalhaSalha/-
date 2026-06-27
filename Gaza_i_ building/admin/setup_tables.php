<?php
require_once __DIR__ . '/../includes/db.php';

try {
    // Messages Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "Messages table created/checked.\n";

    // Settings Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        site_name VARCHAR(100) DEFAULT 'غزة تبني',
        phone VARCHAR(50),
        email VARCHAR(100),
        facebook VARCHAR(255),
        twitter VARCHAR(255),
        instagram VARCHAR(255),
        linkedin VARCHAR(255),
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Insert default settings if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM settings");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO settings (phone, email) VALUES ('1700-500-600', 'support@gaza-builds.ps')");
        echo "Default settings inserted.\n";
    } else {
        echo "Settings table checked.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
