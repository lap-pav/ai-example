<?php
try {
    $pdo = new PDO('mysql:host=db;port=3306;dbname=ai_proj1', 'ai_proj1', 'ai_proj1');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection successful!\n";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Users table accessible. Current user count: " . $result['count'] . "\n";
    
    // Show all tables
    $stmt = $pdo->query("SHOW TABLES");
    echo "✅ Available tables:\n";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "   - " . $row[0] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}
