CREATE TABLE users (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    email text NOT NULL,
    handle text NOT NULL,
    pwd text NOT NULL,
    admin bit
);

CREATE TABLE user_settings (
    user_id int NOT NULL PRIMARY KEY,
    pronouns text NOT NULL,
    bio text NOT NULL,
    links text NOT NULL
);

CREATE TABLE user_favorites (
    user_id int NOT NULL,
    item_id int NOT NULL,
    number int NOT NULL
);

CREATE TABLE follow ( -- För att kolla vem som följer vem
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    from_id int NOT NULL, -- Den som följer
    to_id int NOT NULL -- Den som följs
);

CREATE TABLE ent_types (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL
);

CREATE TABLE ents (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    type text NOT NULL,
    name text NOT NULL, -- Exempel för säsonger: "[Serie Titel] - Season 1"
    handle text NOT NULL, -- Exempelvis "breaking-bad-2008" -- Ska vara unikt inom typgruppen -- Om avsnitt: "[series-handle]-s[num]e[num]-[episode-name] -- Om det finns två av samma titel från samma år: example-title-20XX och example-title-20XX-2
    date text NOT NULL,
    desc text, -- Beskrivning -- NULL för säsonger
    tagline text,
    length int NOT NULL -- Filmer: minuter | Serier: antal säsonger | Säsonger: antal avsnitt | Avsnitt: minuter | Spel: timmar (genomsnitt, avrundas uppåt)
);

CREATE TABLE ent_versions ( -- Theatrical cut, director's cut, extended edition, televised version, etc.
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    parent_id int NOT NULL,
    child_ids json NOT NULL
);

CREATE TABLE collections (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL,
    desc text
);

CREATE TABLE ents_collections (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    item_id int NOT NULL,
    collection_id int NOT NULL
);

CREATE TABLE genres (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL
);

CREATE TABLE genres_types (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    genre_id int NOT NULL,
    type_id int NOT NULL
);

CREATE TABLE items_genres (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    item_id int NOT NULL,
    genre_id int NOT NULL,
    number int NOT NULL
);

CREATE TABLE tags (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL
);

CREATE TABLE tags_types (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tag_id int NOT NULL,
    type_id int NOT NULL
);

CREATE TABLE items_tags (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    item_id int NOT NULL,
    tag_id int NOT NULL,
    number int NOT NULL
);

CREATE TABLE artists (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL,
    bio text
    -- Namn/dir för info.txt och bild.jpg kommer genereras utefter id
);

CREATE TABLE artist_roles (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL
);

CREATE TABLE ents_artists (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ent_id int NOT NULL,
    artist_id int NOT NULL,
    role_id int NOT NULL,
    subrole text, -- Exempelvis karaktärsnamn om skådis
    number int NOT NULL -- Rangordnar listan
);

CREATE TABLE ratings (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    item_id int NOT NULL,
    like bit NOT NULL,
    rating float DEFAULT NULL, -- NULL innebär ingen rating
    created_date datetime NOT NULL,
    edited_date datetime NOT NULL -- Datum för senaste ändringen
);

CREATE TABLE entries (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    ent_id int NOT NULL,
    entry_created_date datetime, -- TODO: Användarens första recension på detta datumet har YYYY-MM-DD 00:01:00, och det andra har YYYY-MM-DD 00:02:00, osv...
    entry_edited_date datetime,
    review_created_date datetime,
    review_edited_date datetime,
    like bit DEFAULT 0 NOT NULL,
    rating float DEFAULT NULL,
    rewatch bit,
    text text,
    spoilers bit
);

CREATE TABLE review_likes (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    entry_id int NOT NULL
);