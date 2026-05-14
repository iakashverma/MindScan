<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/db.php';

try {
    $pdo = get_db();
    
    // Create research_documents table with correct columns
    $sql = "CREATE TABLE IF NOT EXISTS research_documents (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        doc_type VARCHAR(50) NOT NULL,
        description TEXT,
        file_path VARCHAR(255) NOT NULL,
        file_size INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    
    echo "Table research_documents created/verified successfully.";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage();
}
