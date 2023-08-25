CREATE TABLE users (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` text NOT NULL,
    `email` text NOT NULL,
    `uid` text NOT NULL,
    `pwd` text NOT NULL
);

CREATE TABLE user_settings (
    `user_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `pronouns` text NOT NULL,
    `biography` text NOT NULL,
    `links` text NOT NULL
);

CREATE TABLE user_favorites (
    `user_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `item_id` int NOT NULL,
    `number` int NOT NULL
);

CREATE TABLE follow ( -- för att kolla vem som följer vem
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `from_id` int NOT NULL, -- den som följer
    `to_id` int NOT NULL -- den som följs
);

CREATE TABLE types (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` text NOT NULL,
    `uid` text NOT NULL
);

CREATE TABLE items (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `type_id` int NOT NULL,
    `name` text NOT NULL, -- exempel för säsonger: [series-title]/Season 1
    `uid` text NOT NULL, -- exempelvis breaking-bad-2008 -- ska vara unikt inom typgruppen -- om avsnitt: exempelvis breaking-bad-2008/[säsong]/[nummer]-[avsnittets-namn] -- om det finns två av samma titel från samma år: example-title-20XX och example-title-20XX-2
    `year` int NOT NULL,
    `month` int NOT NULL,
    `day` int NOT NULL,
    `description` text, -- beskrivning -- NULL för säsonger
    `tagline` text,
    `length` int NOT NULL -- filmer: minuter, serier: antal säsonger, säsonger: antal avsnitt, avsnitt: minuter, spel: timmar (genomsnitt, avrundas uppåt)
);

CREATE TABLE versions (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `item_id_1` int NOT NULL, -- t.ex. theatrical cut
    `item_id_2` int NOT NULL -- t.ex. director's cut
);

CREATE TABLE collections (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` text NOT NULL,
    `uid` text NOT NULL,
    `description` text
);

CREATE TABLE items_collections (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `item_id` int NOT NULL,
    `collection_id` int NOT NULL
);

CREATE TABLE genres (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` text NOT NULL,
    `uid` text NOT NULL
);

CREATE TABLE genres_types (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `genre_id` int NOT NULL,
    `type_id` int NOT NULL
);

CREATE TABLE items_genres (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `item_id` int NOT NULL,
    `genre_id` int NOT NULL,
    `number` int NOT NULL
);

CREATE TABLE tags (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` text NOT NULL,
    `uid` text NOT NULL
);

CREATE TABLE tags_types (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `tag_id` int NOT NULL,
    `type_id` int NOT NULL
);

CREATE TABLE items_tags (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `item_id` int NOT NULL,
    `tag_id` int NOT NULL,
    `number` int NOT NULL
);

CREATE TABLE crew (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` text NOT NULL,
    `uid` text NOT NULL,
    `description` text
    -- plats för info.txt och bild.jpg kommer genereras utefter id
);

CREATE TABLE crew_roles (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` text NOT NULL,
    `uid` text NOT NULL
);

CREATE TABLE items_crew (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `item_id` int NOT NULL,
    `artist_id` int NOT NULL,
    `role_id` int NOT NULL,
    `character` text, -- om skådis
    `number` int NOT NULL -- rangordnar listan
);

CREATE TABLE ratings (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `item_id` int NOT NULL,
    `like` bit NOT NULL,
    `rating` float DEFAULT NULL, -- NULL innebär ingen rating
    `created_date` datetime NOT NULL,
    `last_edited_date` datetime NOT NULL
);

CREATE TABLE entries (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `item_id` int NOT NULL,
    `log_date` datetime, -- gör att användarens första recension på detta datumet har YYYY-MM-DD 01:00:00, och det andra har YYYY-MM-DD 02:00:00 osv...
    `review_date` datetime,
    `like` bit DEFAULT 0 NOT NULL,
    `rating` float DEFAULT NULL,
    `rewatch` bit,
    `text` text,
    `spoilers` bit
);

CREATE TABLE review_likes (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `entry_id` int NOT NULL
);
