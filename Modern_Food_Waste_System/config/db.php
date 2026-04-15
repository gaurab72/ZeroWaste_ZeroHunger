<?php
// config/db.php

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_Name', 'modern_food_waste_system_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP password is empty

try {
    $dsn = "mysql:host=127.0.0.1;dbname=" . DB_Name . ";charset=utf8mb4;port=3306";
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // This part is critical for the USER to see why it failed locally
    echo "<div style='padding: 30px; background: #fff5f5; color: #721c24; border: 2px solid #f5c6cb; border-radius: 12px; font-family: sans-serif; max-width: 600px; margin: 40px auto; box-shadow: 0 4px 15px rgba(0,0,0,0.1);'>
            <h2 style='margin-top: 0;'>⚠️ Database Connection Error</h2>
            <p><strong>Message:</strong> " . $e->getMessage() . "</p>
            <hr style='border: 0; border-top: 1px solid #f5c6cb; margin: 20px 0;'>
            <p style='font-weight: bold;'>How to fix this:</p>
            <ol style='line-height: 1.6;'>
                <li>Open your <strong>XAMPP Control Panel</strong>.</li>
                <li>Ensure the <strong>MySQL</strong> module is started (it should be green).</li>
                <li>Check if the port is <strong>3306</strong>. If it's different (e.g., 3307), update the 'port' in <code>config/db.php</code>.</li>
                <li>Ensure the database <code>" . DB_Name . "</code> exists in phpMyAdmin.</li>
            </ol>
            <p style='margin-bottom: 0; font-size: 0.9rem; color: #666;'><em>Once MySQL is started, refresh this page.</em></p>
          </div>";
    exit;
}




?>
