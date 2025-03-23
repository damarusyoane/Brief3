<?php
namespace App\Models;

use App\Core\Database;

class User
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getAllUsers()
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserById($id)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function createUser($userData)
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password, role_id, status) 
            VALUES (:username, :email, :password, :role_id, :status)
        ");
        return $stmt->execute($userData);
    }

    public function updateUser($id, $userData)
    {
        $userData['id'] = $id;
        $sql = "UPDATE users SET 
                username = :username,
                email = :email,
                role_id = :role_id,
                status = :status";
        
        if (isset($userData['password'])) {
            $sql .= ", password = :password";
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($userData);
    }

    public function deleteUser($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.username = :username
        ");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.email = :email
        ");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }
}
