
<?php

//use PDO;
//use PDOException;


sleep(20);//wait for database
//get all migration files
$migrationFiles = glob(__DIR__ . '/*.php');
sort($migrationFiles);//sort in order

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

    //create migrations table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    foreach ($migrationFiles as $file) {
        if (basename($file) === 'run.php') continue;

        //check if migration is already executed
        $stmt = $pdo->prepare("SELECT id FROM migrations WHERE migration = ?");
        $stmt->execute([basename($file)]);
        
        if (!$stmt->fetch()) {
            //run migration
            $sql = require $file;
            if (is_string($sql)) {
                $pdo->exec($sql);
                
                //save executed migration
                $stmt = $pdo->prepare("INSERT INTO migrations (migration) VALUES (?)");
                $stmt->execute([basename($file)]);
                
                echo "Executed migration: " . basename($file) . "\n";
            }
        }
    }

    echo "All migrations completed successfully\n";

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
}