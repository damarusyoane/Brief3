<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;

    public function __construct() {
        $this->db = $this->getPDO();
    }

    public function getPDO() {
        try {
            $dsn = 'mysql:host=localhost;dbname=your_database_name;charset=utf8';
            $username = 'your_username';
            $password = 'your_password';
            return new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
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
}