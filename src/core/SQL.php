<?php

namespace App\Core;

use PDO;
use PDOException;

class SQL
{
    private PDO $pdo;

    public function __construct()
    {
        $dsn = "mysql:host=mariadb;dbname=database;charset=utf8";
        try {
            $this->pdo = new PDO($dsn, 'user', 'password');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public function queryPrepared(string $query, array $params = []): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function executePrepared(string $query, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }
}