<?php
require_once __DIR__ . '/app/core/Model.php';

use Model;

class RoleModel extends Model {
    protected $table = 'roles';

    public function getRoles() {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}