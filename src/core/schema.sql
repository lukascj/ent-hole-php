CREATE TABLE IF NOT EXISTS users (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    email text NOT NULL,
    handle text NOT NULL,
    pwd text NOT NULL,
    admin bit
);

CREATE TABLE IF NOT EXISTS user_settings (
    user_id int NOT NULL PRIMARY KEY,
    pronouns text NOT NULL,
    bio text NOT NULL,
    links text NOT NULL
);

CREATE TABLE IF NOT EXISTS user_favorites (
    user_id int NOT NULL,
    item_id int NOT NULL,
    number int NOT NULL
);

CREATE TABLE IF NOT EXISTS follows ( -- För att kolla vem som följer vem
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    from_id int NOT NULL, -- Den som följer
    to_id int NOT NULL -- Den som följs
);

CREATE TABLE IF NOT EXISTS types (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL
);

CREATE TABLE IF NOT EXISTS ents (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    type text NOT NULL,
    name text NOT NULL, -- Exempel för säsonger: "[Serie Titel] - Season 1"
    handle text NOT NULL, -- Exempelvis "breaking-bad-2008" -- Ska vara unikt inom typgruppen -- Om avsnitt: "[series-handle]-s[num]e[num]-[episode-name] -- Om det finns två av samma titel från samma år: example-title-20XX och example-title-20XX-2
    date text NOT NULL,
    `desc` text, -- Beskrivning -- NULL för säsonger
    tagline text,
    length int NOT NULL -- Filmer: minuter | Serier: antal säsonger | Säsonger: antal avsnitt | Avsnitt: minuter | Spel: timmar (genomsnitt, avrundas uppåt)
);

CREATE TABLE IF NOT EXISTS ent_versions ( -- Theatrical cut, director's cut, extended edition, televised version, etc.
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    parent_id int NOT NULL,
    child_ids json NOT NULL
);

CREATE TABLE IF NOT EXISTS collections (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL,
    `desc` text
);

CREATE TABLE IF NOT EXISTS ents_collections (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ent_id int NOT NULL,
    collection_id int NOT NULL
);

CREATE TABLE IF NOT EXISTS genres (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL
);

CREATE TABLE IF NOT EXISTS genres_types (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    genre_id int NOT NULL,
    type_id int NOT NULL
);

CREATE TABLE IF NOT EXISTS ents_genres (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ent_id int NOT NULL,
    genre_id int NOT NULL,
    number int NOT NULL
);

CREATE TABLE IF NOT EXISTS tags (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL
);

CREATE TABLE IF NOT EXISTS tags_types (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tag_id int NOT NULL,
    type_id int NOT NULL
);

CREATE TABLE IF NOT EXISTS ents_tags (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ent_id int NOT NULL,
    tag_id int NOT NULL,
    number int NOT NULL
);

CREATE TABLE IF NOT EXISTS artists (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL,
    bio text
    -- Namn/dir för info.txt och bild.jpg kommer genereras utefter id
);

CREATE TABLE IF NOT EXISTS artist_roles (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name text NOT NULL,
    handle text NOT NULL
);

CREATE TABLE IF NOT EXISTS ents_artists (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ent_id int NOT NULL,
    artist_id int NOT NULL,
    role_id int NOT NULL,
    subrole text, -- Exempelvis karaktärsnamn om skådis
    number int NOT NULL -- Rangordnar listan
);

CREATE TABLE IF NOT EXISTS ratings (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    ent_id int NOT NULL,
    `like` bit NOT NULL,
    rating float DEFAULT NULL, -- NULL innebär ingen rating
    created_date datetime NOT NULL,
    edited_date datetime NOT NULL -- Datum för senaste ändringen
);

CREATE TABLE IF NOT EXISTS entries (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    ent_id int NOT NULL,
    entry_created_date datetime NOT NULL, -- TODO: Användarens första recension på detta datumet har YYYY-MM-DD 00:01:00, och det andra har YYYY-MM-DD 00:02:00, osv...
    entry_edited_date datetime,
    review_created_date datetime,
    diary_watched_date datetime,
    `like` bit DEFAULT 0 NOT NULL,
    rating float DEFAULT NULL,
    rewatch bit,
    review_text text,
    spoilers bit
);

CREATE TABLE IF NOT EXISTS review_likes (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    entry_id int NOT NULL
);