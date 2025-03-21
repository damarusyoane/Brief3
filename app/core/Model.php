<?php
class Model {
    protected $db;

    public function __construct() {
        // Connexion à la base de données
        $this->db = new Database();
    }

    // Méthode générique pour exécuter des requêtes SQL
    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Méthode pour récupérer tous les enregistrements
    protected function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer un seul enregistrement
    protected function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
}