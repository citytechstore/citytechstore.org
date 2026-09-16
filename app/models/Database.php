<?php
// require_once ('../database/db_config.php'); // dev
require_once ('app/database/db_config.php'); // prod

class Database {
    private $conn;

    public function __construct($database_connection) {
        $this->conn = $database_connection;
    }
    public function prepare($sql){
        return $this->conn->prepare($sql);
    }
    
    public function query($sql){
        return $this->conn->query($sql);
    }

    public function beginTransaction() {
        return $this->conn->begin_transaction();
    }

    public function commit() {
        return $this->conn->commit();
    }

    public function rollback() {
        return $this->conn->rollback();
    }

    public function array_insert($tablename, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $tablename ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $types = str_repeat('s', count($data)); // Assuming all data are strings for simplicity
        $stmt->bind_param($types, ...array_values($data));

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();
    }

    public function array_update($tablename, $data, $where) {
        $setClause = implode(" = ?, ", array_keys($data)) . " = ?";
        $whereClause = implode(" = ? AND ", array_keys($where)) . " = ?";

        $sql = "UPDATE $tablename SET $setClause WHERE $whereClause";
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $types = str_repeat('s', count($data) + count($where)); // Assuming all data are strings for simplicity
        $stmt->bind_param($types, ...array_merge(array_values($data), array_values($where)));

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();
    }
}


$DatabaseModel = new Database($mysql_conn);