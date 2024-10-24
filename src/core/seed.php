<?php
class Seed {
    private $_conn;

    public function __construct($conn) {
        $this->_conn = $conn;
    }

    public function reset_db($createBackup = false) {
        $query = "SET @tables = NULL;
        SELECT GROUP_CONCAT('`', table_name, '`') INTO @tables
        FROM information_schema.tables
        WHERE table_schema = (SELECT DATABASE());
        SELECT IFNULL(@tables,'dummy') INTO @tables;
        SET @tables = CONCAT('DROP TABLE IF EXISTS ', @tables);
        PREPARE stmt FROM @tables;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;";
        $stmt = $this->_conn->prepare($query);
        $stmt->execute([]);
    }
}
