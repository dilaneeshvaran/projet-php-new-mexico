<?php

function runMigrations($pdo) {
    $migrationFiles = glob(__DIR__ . '/*.php');
    sort($migrationFiles);
   
    $migrationsRun = false;
    foreach ($migrationFiles as $file) {
        if (basename($file) === 'run_migrations.php') continue;
        $stmt = $pdo->prepare("SELECT id FROM migrations WHERE migration = ?");
        $stmt->execute([basename($file)]);
       
        if (!$stmt->fetch()) {
            $sql = require $file;
            if (is_string($sql)) {
                $pdo->exec($sql);
               
                $stmt = $pdo->prepare("INSERT INTO migrations (migration) VALUES (?)");
                $stmt->execute([basename($file)]);
               
                echo "Executed migration: " . basename($file) . "\n";
                $migrationsRun = true;
            }
        }
    }
    return $migrationsRun;
}

// wait for db
sleep(10);
$dsn = sprintf(
    'mysql:host=mariadb;dbname=%s',
    getenv('DATABASE_NAME')
);

try {
    $pdo = new PDO(
        $dsn,
        getenv('DATABASE_USER'),
        getenv('DATABASE_PASSWORD')
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    if (!runMigrations($pdo)) {
        echo "No new migrations to execute.\n";
    }
   
    echo "Migration process completed successfully.\n";
    exit(0); //exit with success
    
} catch (PDOException $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    exit(1);
}