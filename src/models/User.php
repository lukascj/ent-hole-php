<?php
class User {
    private $_conn;
    
    public function __construct($conn) {
        $this->_conn = $conn; // Inject databaskoppling.
    }

    public function fetch($handle) {
        $stmt = $this->_conn->prepare("SELECT * FROM users WHERE handle = ?");
        $stmt->execute([$handle]);
        return $stmt->fetch();
    }

    public function fetch_by_id($id) {
        $stmt = $this->_conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function fetch_activity($id, $lim = 19) {
 
        $query = "SELECT entries.user_id AS user_id, 
            users.handle AS user_handle, 
            users.name AS username, 
            entries.id AS entry_id, 
            entries.like AS `like`, 
            entries.rating AS rating, 
            entries.ent_id AS ent_id, 
            entries.entry_created_date AS `date`,
            entries.review_created_date AS review_date, 
            entries.entry_edited_date AS edited_date,
            entries.review_text AS review_text, 
            entries.rewatch AS rewatch, 
            entries.spoilers AS spoilers,  
            ents.handle AS ent, 
            ents.type AS ent_type, 
            ents.name AS ent_title, 
            ents.date AS ent_date 
        FROM entries 
        INNER JOIN ents ON ents.id = entries.ent_id 
        INNER JOIN follows ON follows.to_id = entries.user_id 
        INNER JOIN users ON users.id = follows.to_id 
        WHERE follows.to_id IN (SELECT to_id FROM follows WHERE from_id = ?)
        ORDER BY `date` DESC 
        LIMIT ?";

        $stmt = $this->_conn->prepare($query);
        $stmt->execute([$id, $lim]);
        return $stmt->fetch();
    }

    public function save($data) {
        $stmt = $this->_conn->prepare("INSERT INTO users (name, email, pwd) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'], $data['email'], password_hash($data['pwd'], PASSWORD_BCRYPT)]);
    }
}