<?php
namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static $instance = null;
    private $host = 'localhost';
    private $db_name = 'gestion_utilisateurs';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function __construct()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    public function prepare($sql): PDOStatement
    {
        return $this->conn->prepare($sql);
    }
}
