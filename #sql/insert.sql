-- TYPES:

INSERT INTO `types` (`id`, `name`, `uid`) 
VALUES ('1', 'Film', 'film');

CREATE TABLE items_attr_films (
    `film_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT
);

INSERT INTO `types` (`id`, `name`, `uid`) 
VALUES ('2', 'Short Film', 'short-film');

CREATE TABLE items_attr_short_films (
    `short_film_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT
);

INSERT INTO `types` (`id`, `name`, `uid`) 
VALUES ('3', 'Series', 'series');

CREATE TABLE items_attr_series (
    `series_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT, -- för att koppla
    `limited` bit NOT NULL, -- anger om det är en limited serie eller inte
    `length_episodes` int NOT NULL, -- längden i antal avsnitt -- length (i items tabellen) är antalet säsonger
    `length_minutes` int NOT NULL, -- avsnittens genomsnittliga längd
    `finale_year` int, -- NULL om pågående
    `finale_month` int,
    `finale_day` int
);

INSERT INTO `types` (`id`, `name`, `uid`) 
VALUES ('4', 'Season', 'season');

CREATE TABLE items_attr_seasons (
    `season_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT, 
    `series_id` int NOT NULL, -- för att koppla till serie
    `number` int NOT NULL -- säsongens nummer i serien
);

INSERT INTO `types` (`id`, `name`, `uid`) 
VALUES ('5', 'Episode', 'episode');

CREATE TABLE items_attr_episodes (
    `episode_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `series_id` int NOT NULL,
    `season_id` int NOT NULL,
    `number_series` int NOT NULL, -- avsnittets nummer i serien
    `number_season` int NOT NULL -- avsnittets nummer i säsongen
);

INSERT INTO `types` (`id`, `name`, `uid`) 
VALUES ('6', 'Game', 'game');

INSERT INTO `types` (`id`, `name`, `uid`) 
VALUES ('7', 'Album', 'album');

INSERT INTO `types` (`id`, `name`, `uid`) 
VALUES ('8', 'Song', 'song');

CREATE TABLE items_attr_songs (
    `song_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `single` bit NOT NULL -- har den släppts som single? (den kommer då hitta egen cover-bild)
);

CREATE TABLE songs_albums (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `song_id` int NOT NULL,
    `album_id` int NOT NULL,
    `number` int NOT NULL-- nummer i album
);

INSERT INTO `types` (`id`, `name`, `uid`) 
VALUES ('9', 'Book', 'book');

-- GENRES:

-- främst för film:
INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('1', 'Action', 'action');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('1', '1');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('1', '2');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('1', '3');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('1', '4');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('1', '5');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('1', '6');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('1', '9');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('2', 'Adventure', 'adventure');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('2', '1');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('2', '2');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('2', '3');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('2', '4');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('2', '5');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('2', '6');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('2', '9');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('3', 'Animation', 'animation');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('3', '1');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('3', '2');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('3', '3');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('3', '4');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('3', '5');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('4', 'Biography', 'biography');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('4', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('5', 'Comedy', 'comedy');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('5', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('6', 'Crime', 'crime');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('6', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('7', 'Documentary', 'documentary');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('7', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('8', 'Drama', 'drama');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('8', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('9', 'Family', 'family');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('9', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('10', 'Fantasy', 'fantasy');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('10', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('11', 'Horror', 'horror');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('11', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('12', 'Music', 'music');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('12', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('13', 'Mystery', 'mystery');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('13', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('14', 'Romance', 'romance');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('14', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('15', 'Sci-Fi', 'sci-fi');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('15', '1');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('16', 'Thriller', 'thriller');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('16', '1');

-- främst för spel:
INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('17', 'Puzzle', 'puzzle');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('17', '6');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('18', 'RP', 'rp');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('18', '6');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('19', 'Simulation', 'simulation');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('19', '6');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('20', 'Strategy', 'strategy');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('20', '6');

INSERT INTO `genres` (`id`, `name`, `uid`) VALUES ('21', 'Multiplayer', 'multiplayer');
INSERT INTO `genres_types` (`genre_id`, `type_id`) VALUES ('21', '6');

-- TAGS:

-- främst för film:
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('1', 'Superheroes', 'superheroes');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('2', 'Vampires', 'vampires');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('3', 'Samurai', 'samurai');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('4', 'Splatter', 'splatter');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('5', 'Slasher', 'slasher');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('6', 'Whodunnit', 'whodunnit');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('7', 'Noir', 'noir');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('8', 'Neo-Noir', 'neo-noir');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('9', 'Handdrawn Animation', 'handdrawn-animation');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('10', 'CG Animation', 'cg-animation');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('11', 'Stop-Motion', 'stop-motion');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('12', 'War', 'war');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('13', 'Western', 'western');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('14', 'Psychological', 'psychological');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('15', 'Mockumentary', 'mockumentary');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('16', 'Found Footage', 'found-footage');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('17', 'Dinosaurs', 'dinosaurs');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('18', 'Slapstick', 'slapstick');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('19', 'Black Comedy', 'black-comedy');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('20', 'Werewolves', 'werewolves');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('21', 'Coming of Age', 'coming-of-age');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('22', 'Teenagers', 'teenagers');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('23', 'Adolescence', 'adolescence');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('24', 'Cars', 'cars');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('25', 'Pirates', 'pirates');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('26', 'Vikings', 'vikings');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('27', 'Monsters', 'monsters');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('28', 'Spies', 'spies');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('29', 'Post-Apocalypse', 'post-apocalypse');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('30', 'Dystopian', 'dystopian');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('31', 'Utopian', 'utopian');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('32', 'Martial-Arts', 'martial-arts');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('33', 'Zombies', 'zombies');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('34', 'Heists', 'heists');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('35', 'Gore', 'gore');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('36', 'Aliens', 'aliens');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('37', 'Ghosts', 'ghosts');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('38', 'Time Travel', 'time-travel');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('39', 'School', 'school');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('40', 'Courtroom', 'courtroom');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('41', 'Disaster', 'disaster');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('42', 'History', 'history');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('43', 'Future', 'future');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('44', 'Anime', 'anime');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('45', 'Spaghetti Western', 'spaghetti-western');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('46', 'Sports', 'sports');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('47', 'Space', 'space');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('48', 'Cowboys', 'cowboys');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('49', 'Nudity', 'nudity');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('50', 'Racing', 'racing');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('51', 'Underwater', 'underwater');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('52', 'Isolation', 'isolation');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('53', 'Loneliness', 'loneliness');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('54', 'Love', 'love');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('55', 'Detectives', 'detectives');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('56', 'Body Horror', 'body-horror');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('81', 'Drugs', 'drugs');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('82', 'Alcohol', 'alcohol');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('83', 'Violence', 'violence');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('84', 'Kids', 'kids');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('85', 'Planes', 'planes');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('86', 'Boats', 'boats');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('87', 'Wilderness', 'wilderness');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('88', 'Exotic Locations', 'exotic-locations');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('89', 'Medieval', 'medieval');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('90', 'Knights', 'knights');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('91', 'Mythology', 'mythology');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('92', 'Norse Mythology', 'norse-mythology');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('93', 'Greek Mythology', 'greek-mythology');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('94', 'Egyptian Mythology', 'egyptian-mythology');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('95', 'Christian Mythology', 'christian-mythology');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('96', 'Arthurian', 'arthurian');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('97', 'Shakespeare', 'shakespeare');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('98', 'High-School', 'high-school');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('99', 'College', 'college');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('100', 'Epic', 'epic');

-- framst för spel:
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('57', 'Platformer', 'platformer');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('58', 'Collectathon', 'collectathon');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('59', 'Metroidvania', 'metroidvania');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('60', 'Roguelike', 'roguelike');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('61', 'Cards', 'cards');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('62', 'Board', 'board');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('63', '2D', '2d');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('64', '3D', '3d');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('65', 'Shooter', 'shooter');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('66', 'First-Person', 'first-person');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('67', 'Third-Person', 'third-person');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('68', 'Sandbox', 'sandbox');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('69', 'Exploration', 'exploration');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('70', 'Open-World', 'open-world');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('71', 'Story-Heavy', 'story-heavy');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('72', 'MMO', 'mmo');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('73', 'Competetive', 'competetive');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('74', 'Casual', 'casual');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('75', 'Turn-Based', 'turn-based');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('76', 'Fighting', 'fighting');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('77', 'Tower Defence', 'tower-defence');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('78', 'Construction', 'construction');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('79', 'Randomly Generated', 'randomly-generated');
INSERT INTO `tags` (`id`, `name`, `uid`) VALUES ('80', 'Party', 'party');

-- CREW ROLES:

-- främst för film:
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('1', 'Actor', 'actor');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('2', 'Director', 'director');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('3', 'Writer', 'writer');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('4', 'Cinematographer', 'cinematographer');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('5', 'Composer', 'composer');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('6', 'Producer', 'producer');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('7', 'Executive Producer', 'executive-producer');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('8', 'Editor', 'editor');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('9', 'Production Designer', 'production-designer');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('10', 'Art Director', 'art-director');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('11', 'Sound', 'sound');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('12', 'Costume', 'costume');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('13', 'Hair', 'hair');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('14', 'Make-Up', 'make-up');

-- främst för böcker:
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('15', 'Author', 'author');
INSERT INTO `crew_roles` (`id`, `name`, `uid`) VALUES ('16', 'Book Editor', 'book-editor');

-- CREW:
INSERT INTO `crew` (`id`, `name`, `uid`, `description`) VALUES ('1', 'Bryan Cranston', 'bryan-cranston', '');
INSERT INTO `crew` (`id`, `name`, `uid`, `description`) VALUES ('2', 'Bob Odenkirk', 'bob-odenkirk', '');
INSERT INTO `crew` (`id`, `name`, `uid`, `description`) VALUES ('3', 'Aaron Paul', 'aaron-paul', '');

-- COLLECTIONS:
INSERT INTO `collections` (`id`, `name`, `uid`) VALUES ('1', 'Breakage Collection', 'breakage-collection');
INSERT INTO `items_collections` (`id`, `item_id`, `collection_id`) VALUES ('2', '4', '1');

-- ITEMS:

-- breaking bad:
INSERT INTO `items` (`id`, `type_id`, `name`, `uid`, `year`, `month`, `day`, `description`, `tagline`, `length`) 
VALUES ('1', '3', 'Breaking Bad', 'breaking-bad-2008', '2008', '1', '20', "A chemistry teacher diagnosed with inoperable lung cancer turns to manufacturing and selling methamphetamine with a former student in order to secure his family's future.", 'Change [the] Equation.', '5');

INSERT INTO `items_attr_series` (`series_id`, `limited`, `length_episodes`, `length_minutes`, `finale_year`, `finale_month`, `finale_day`) 
VALUES ('1', 0, '62', '45', '2013', '9', '29');

INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('1', '8', '1'); -- drama
INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('1', '16', '2'); -- thriller
INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('1', '6', '3'); -- crime

INSERT INTO `items_crew` (`item_id`, `artist_id`, `role_id`, `character`, `number`) VALUES ('1', '1', '1', 'Walter White', '1'); -- bryan cranston
INSERT INTO `items_crew` (`item_id`, `artist_id`, `role_id`, `character`, `number`) VALUES ('1', '3', '1', 'Jesse Pinkman', '2'); -- aaron paul
INSERT INTO `items_crew` (`item_id`, `artist_id`, `role_id`, `character`, `number`) VALUES ('1', '2', '1', 'Saul Goodman', '3'); -- bob odenkirk

INSERT INTO `items_collections` (`item_id`, `collection_id`) VALUES ('1', '1'); -- breakage collection

-- breakfast club:
INSERT INTO `items` (`id`, `type_id`, `name`, `uid`, `year`, `month`, `day`, `description`, `tagline`, `length`) 
VALUES ('2', '1', 'The Breakfast Club', 'breakfast-club-1985', '1985', '7', '5', 'Five high school students meet in Saturday detention and discover how they have a lot more in common than they thought.', 'They only met once, but it changed their lives forever.', '97');

INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('2', '8', '1'); -- drama

-- barbarian:
INSERT INTO `items` (`id`, `type_id`, `name`, `uid`, `year`, `month`, `day`, `description`, `tagline`, `length`) 
VALUES ('3', '1', 'Barbarian', 'barbarian-2022', '2022', '9', '9', 'In town for a job interview, a young woman arrives at her Airbnb late at night only to find that it has been mistakenly double-booked.', 'Come for a night. Stay forever.', '103');

INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('3', '11', '1'); -- horror
INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('3', '16', '2'); -- thriller
INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('3', '13', '3'); -- mystery

-- better call saul:
INSERT INTO `items` (`id`, `type_id`, `name`, `uid`, `year`, `month`, `day`, `description`, `tagline`, `length`) 
VALUES ('4', '3', 'Better Call Saul', 'better-call-saul-2015', '2015', '2', '8', "The trials and tribulations of criminal lawyer Jimmy McGill in the years leading up to his fateful run-in with Walter White and Jesse Pinkman.", 'Make the Call', '6');

INSERT INTO `items_attr_series` (`series_id`, `limited`, `length_episodes`, `length_minutes`, `finale_year`, `finale_month`, `finale_day`) 
VALUES ('4', 0, '62', '45', '2013', '9', '29');

INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('4', '8', '1'); -- drama
INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('4', '6', '2'); -- crime

INSERT INTO `items_crew` (`item_id`, `artist_id`, `role_id`, `character`, `number`) VALUES ('4', '2', '1', 'Jimmy McGill', '1');

-- northman:
INSERT INTO `items` (`id`, `type_id`, `name`, `uid`, `year`, `month`, `day`, `description`, `tagline`, `length`) 
VALUES ('5', '1', 'Northman', 'northman-2022', '2022', '4', '22', "A young Viking prince is on a quest to avenge his father's murder.", 'Conquer your fate.', '137');

INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('5', '1', '1'); -- action
INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('5', '16', '2'); -- thriller
INSERT INTO `items_genres` (`item_id`, `genre_id`, `number`) VALUES ('5', '8', '3'); -- drama