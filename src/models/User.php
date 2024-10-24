<?php
class User {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn; // Inject databaskoppling.
    }

    public function find($handle) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE handle = ?");
        $stmt->execute([$handle]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function save($data) {
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, pwd) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'], $data['email'], password_hash($data['pwd'], PASSWORD_BCRYPT)]);
    }
}