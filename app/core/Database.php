<?php
class Database {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'gestion_utilisateurs';
    private $dbh;
    private $stmt;

    public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->dbh = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die("Database connection failed. Please check your configuration.");
        }
    }

    // Préparer une requête SQL
    public function prepare($sql) {
        $this->stmt = $this->dbh->prepare($sql);
        return $this->stmt;
    }

    // Exécuter une requête préparée
    public function execute($params = []) {
        return $this->stmt->execute($params);
    }

    // Récupérer tous les résultats
    public function fetchAll() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Récupérer un seul résultat
    public function fetch() {
        $this->execute();
        return $this->stmt->fetch();
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    public function commit() {
        return $this->dbh->commit();
    }

    public function rollBack() {
        return $this->dbh->rollBack();
    }
}