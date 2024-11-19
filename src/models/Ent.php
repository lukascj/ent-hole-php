<?php
class Ent {
    private $_conn;

    public function __construct($conn) {
        $this->_conn = $conn;
    }

    public function fetch_popular($by = "week", $lim = 19) {
        $date = new DateTime();
        if(!in_array($by, ["day", "week", "month", "year", "all-time"])) {
            $dateStr = $date->modify('-7 days')->format('Y-m-d H-i-s'); 
        } else {
            if($by == "month") {
                $dateStr = $date->modify('-30 days')->format('Y-m-d H-i-s');
            } else {
                $dateStr = $date->modify('-7 days')->format('Y-m-d H-i-s');
            }
        }

        // Hämta ents sorterade efter popularitet.
        // Popularitet bestäms utefter hur många som har interagerat med objektet nyligen.
        // Hur många har skapat en entry alternativt en rating för ent:en över senaste dagen/veckan/månaden/året/någonsin?
        // Max 1 per user.

        $query = "SELECT 
                ents.*, 
                COUNT(interactions.user_id) AS popularity 
            FROM ents 
            LEFT JOIN 
                (
                    SELECT 
                        interactions.ent_id, 
                        interactions.user_id, 
                        MAX(interactions.`date`) AS latest_interaction 
                    FROM 
                        (
                            SELECT 
                                entries.entry_created_date AS `date`, 
                                entries.user_id AS user_id, 
                                entries.ent_id AS ent_id 
                            FROM 
                                entries 
                            UNION 
                            SELECT 
                                ratings.created_date AS `date`, 
                                ratings.user_id AS user_id, 
                                ratings.ent_id AS ent_id 
                            FROM 
                                ratings
                        ) AS interactions 
                    WHERE 
                        interactions.date > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s') 
                    GROUP BY 
                        interactions.ent_id, interactions.user_id
                ) AS interactions ON interactions.ent_id = ents.id 
            GROUP BY ents.id 
            ORDER BY popularity DESC, `name` DESC
            LIMIT ?";

        $params = [$dateStr, $lim];
        $query = "SELECT *
            FROM ents
            ORDER BY `name`";

        $result = $this->_conn->
            execute_query($query)->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    public function fetch_popular_in_genre($genre, $by = "week", $lim = 19) {
        $date = new DateTime();
        if(!in_array($by, ["day", "week", "month", "year", "all-time"])) {
            $dateStr = $date->modify('-7 days')->format('Y-m-d H-i-s'); 
        } else {
            if($by == "month") {
                $dateStr = $date->modify('-30 days')->format('Y-m-d H-i-s');
            } else {
                $dateStr = $date->modify('-7 days')->format('Y-m-d H-i-s');
            }
        }

        $query = "SELECT 
                ents.*, 
                COUNT(interactions.user_id) AS popularity 
            FROM 
                (
                    SELECT ents.*
                    FROM ents
                    INNER JOIN
                        ents_genres ON ent_id = ents.id
                    INNER JOIN
                        genres ON ents_genres.genre_id = genres.id
                    WHERE genres.handle = ?
                    ORDER BY ents.`name`
                ) AS ents 
            LEFT JOIN 
                (
                    SELECT 
                        all_interactions.ent_id, 
                        all_interactions.user_id, 
                        MAX(all_interactions.`date`) AS latest_interaction 
                    FROM 
                        (
                            SELECT 
                                entries.entry_created_date AS `date`, 
                                entries.user_id AS user_id, 
                                entries.ent_id AS ent_id 
                            FROM entries 
                            UNION 
                            SELECT 
                                ratings.created_date AS `date`, 
                                ratings.user_id AS user_id, 
                                ratings.ent_id AS ent_id 
                            FROM ratings
                        ) AS all_interactions 
                    WHERE 
                        all_interactions.date > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s') 
                    GROUP BY 
                        all_interactions.ent_id, all_interactions.user_id
                ) AS interactions ON interactions.ent_id = ents.id 
            GROUP BY ents.id 
            ORDER BY popularity DESC, `name` DESC 
            LIMIT ?";
            
        $params = [$genre, $dateStr, $lim];

        $result = $this->_conn->
            execute_query($query, $params)->fetch_all(MYSQLI_ASSOC);
        print_r($result);
        return $result;
    }

    public function fetch_highest_rated($lim = 19) {

        $query = "SELECT 
                ents.*, 
                AVG(ratings.rating) AS avg_rating,
                COUNT(ratings.rating) AS ratings_count 
            FROM 
                ents 
            LEFT JOIN 
                ratings ON ratings.ent_id = ents.id 
            GROUP BY ents.id
            ORDER BY avg_rating DESC 
            LIMIT ?";

        return $this->_conn->execute_query($query, [$lim])->fetch_all(MYSQLI_ASSOC);
    }

    public function fetch_random($lim = 19) {

        $query = "SELECT *
            FROM 
                ents 
            ORDER BY RAND() 
            LIMIT ?";

        return $this->_conn->execute_query($query, [$lim])->fetch_all(MYSQLI_ASSOC);
    }
}