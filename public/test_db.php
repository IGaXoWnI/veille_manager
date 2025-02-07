<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/Database.php';

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    if ($connection) {
        echo "✅ Database connection successful!<br>";
        
        $stmt = $connection->query("SELECT version()");
        $version = $stmt->fetch();
        echo "PostgreSQL Version: " . $version[0] . "<br>";
        
        $tables = $connection->query("
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_schema = 'public'
        ")->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<br>Found tables:<br>";
        foreach ($tables as $table) {
            echo "📋 " . $table . "<br>";
        }
    }
} catch (PDOException $e) {
    echo "❌ Connection failed:<br>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Code: " . $e->getCode() . "<br>";
    
    echo "<br>Common solutions:<br>";
    echo "1. Check if PostgreSQL is running<br>";
    echo "2. Verify database name is correct<br>";
    echo "3. Verify username and password<br>";
    echo "4. Check if database exists<br>";
}