<?php

class Model {
    protected $db;
    protected $table;

    public function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->db = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed. Please try again later.");
        }
    }

    protected function query($sql, $params = []) {
        try {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
        } catch (PDOException $e) {
            error_log("Query failed: " . $e->getMessage());
            return false;
        }
    }

    protected function findAll($conditions = [], $orderBy = null, $limit = null, $offset = null) {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $params = [];

            if (!empty($conditions)) {
                $whereClauses = [];
                foreach ($conditions as $key => $value) {
                    $whereClauses[] = "{$key} = :{$key}";
                    $params[$key] = $value;
                }
                $sql .= " WHERE " . implode(' AND ', $whereClauses);
            }

            if ($orderBy) {
                $sql .= " ORDER BY {$orderBy}";
            }

            if ($limit) {
                $sql .= " LIMIT :limit";
                $params['limit'] = $limit;
            }

            if ($offset) {
                $sql .= " OFFSET :offset";
                $params['offset'] = $offset;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Find all failed: " . $e->getMessage());
            return [];
        }
    }

    protected function findOne($conditions) {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $params = [];

            if (!empty($conditions)) {
                $whereClauses = [];
                foreach ($conditions as $key => $value) {
                    $whereClauses[] = "{$key} = :{$key}";
                    $params[$key] = $value;
                }
                $sql .= " WHERE " . implode(' AND ', $whereClauses);
            }

            $sql .= " LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Find one failed: " . $e->getMessage());
            return false;
        }
    }

    protected function create($data) {
        try {
            $columns = implode(', ', array_keys($data));
            $values = implode(', :', array_keys($data));
            $sql = "INSERT INTO {$this->table} ({$columns}) VALUES (:{$values})";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Create failed: " . $e->getMessage());
            return false;
        }
    }

    protected function update($id, $data) {
        try {
            $setClauses = [];
            foreach ($data as $key => $value) {
                $setClauses[] = "{$key} = :{$key}";
            }
            $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses) . " WHERE id = :id";

            $data['id'] = $id;
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Update failed: " . $e->getMessage());
            return false;
        }
    }

    protected function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Delete failed: " . $e->getMessage());
            return false;
        }
    }

    protected function count($conditions = []) {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table}";
            $params = [];

            if (!empty($conditions)) {
                $whereClauses = [];
                foreach ($conditions as $key => $value) {
                    $whereClauses[] = "{$key} = :{$key}";
                    $params[$key] = $value;
                }
                $sql .= " WHERE " . implode(' AND ', $whereClauses);
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch()['count'];
        } catch (PDOException $e) {
            error_log("Count failed: " . $e->getMessage());
            return 0;
        }
    }

    protected function beginTransaction() {
        return $this->db->beginTransaction();
    }

    protected function commit() {
        return $this->db->commit();
    }

    protected function rollback() {
        return $this->db->rollBack();
    }
}