<?php
include_once 'conn.php';

class Seed {
    private $_conn;
    private $_tablesQuery = "SELECT GROUP_CONCAT(table_name) AS tables FROM information_schema.tables WHERE table_schema = (SELECT DATABASE())";

    public function __construct($conn) {
        $this->_conn = $conn;
    }

    public function reset_db($createBackup = false) {
        try {
            // Steg 1: Hämta alla tabellnamn
            $stmt = $this->_conn->query($this->_tablesQuery);
            $result = $stmt->fetch_array();

            if($result && $result['tables']) {
                $tables = explode(',', $result['tables']);
                
                // Steg 2: Släng tabellerna individuellt
                foreach($tables as $table) {
                    $dropQuery = "DROP TABLE IF EXISTS `$table`";
                    $this->_conn->execute_query($dropQuery);
                }
                echo "Database reset completed!<br>";
            } else {
                echo "No tables to drop.<br>";
            }
        } catch(Exception $e) {
            // Logga error
            echo "Database reset failed: " . $e->getMessage() . "<br>"; // Hatar att PHP inte är konsekvent med benämning på funktioner. camelCase från ingenstans
        }
    }

    public function apply_schema() {
        try {
            // Kollar att databasen är tom
            $stmt = $this->_conn->query($this->_tablesQuery);
            $result = $stmt->fetch_array();
            if($result && $result['tables']) {
                throw new Exception("Tables already exists.");
            }

            // SQL från extern fil
            $sql = file_get_contents('sql/schema.sql');

            // Kör flera queries
            if($this->_conn->multi_query($sql)) {
                do {
                    // Förvara första resultat-settet
                    if($result = $this->_conn->store_result()) {
                        $result->free();
                    }
                } while($this->_conn->next_result()); // Gå vidare till nästa resultat
                echo "Database schema applied!<br>";
            } else {
                echo "Error executing SQL: " . $this->_conn->error . "<br>";
            }
        } catch(Exception $e) {
            echo "Database schema write failed: " . $e->getMessage() . "<br>";
        }
    }

    public function populate() {
        try {
            $sql = file_get_contents('sql/inserts.sql');

            if($this->_conn->multi_query($sql)) {
                do {
                    if($result = $this->_conn->store_result()) {
                        $result->free();
                    }
                } while($this->_conn->next_result());
                echo "Database seeding completed!<br>";
            } else {
                echo "Error executing SQL: " . $this->_conn->error . "<br>";
            }
        } catch(Exception $e) {
            echo "Database seeding failed: " . $e->getMessage() . "<br>";
        }
    }

    public function see_all() {
        // Hämta alla tabeller i databasen
        $tables = $this->_conn->query("SHOW TABLES");

        if ($tables->num_rows > 0) {
            // Loopa genom tabellerna
            while ($table = $tables->fetch_array()) {
                $tableName = $table[0];
                echo "<h2>Table: $tableName</h2>";

                // Alla rader från tabellen
                $rows = $this->_conn->query("SELECT * FROM `$tableName`");

                if ($rows->num_rows > 0) {
                    // Fetcha och visa rader
                    while ($row = $rows->fetch_assoc()) {
                        echo "<pre>";
                        print_r($row); // Associativ array
                        echo "</pre>";
                    }
                } else {
                    echo "Table is empty.<br>";
                }
            }
        } else {
            echo "No tables found in the database.<br>";
        }
    }
}

echo "Running seed.php<br>";

$seed = new Seed($conn);

$seed->reset_db();
$seed->apply_schema();
$seed->populate();
$seed->see_all();

$conn->close();