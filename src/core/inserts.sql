-- TYPES:
INSERT INTO types (id, name, handle) VALUES 
(1, 'Film', 'film'),
(2, 'Series', 'series'),
(3, 'Season', 'season'),
(4, 'Episode', 'episode'),
(5, 'Game', 'game'),
(6, 'Book', 'book');

-- GENRES:

-- Främst för film:
INSERT INTO genres (id, name, handle) VALUES
(1, 'Action', 'action'),
(2, 'Adventure', 'adventure'),
(3, 'Animation', 'animation'),
(4, 'Biography', 'biography'),
(5, 'Comedy', 'comedy'),
(6, 'Crime', 'crime'),
(7, 'Documentary', 'documentary'),
(8, 'Drama', 'drama'),
(9, 'Family', 'family'),
(10, 'Fantasy', 'fantasy'),
(11, 'Horror', 'horror'),
(12, 'Music', 'music'),
(13, 'Mystery', 'mystery'),
(14, 'Romance', 'romance'),
(15, 'Sci-Fi', 'sci-fi'),
(16, 'Thriller', 'thriller');

-- Främst för spel:
INSERT INTO genres (id, name, handle) VALUES
(17, 'Puzzle', 'puzzle'),
(18, 'RPG', 'rpg'),
(19, 'Simulation', 'simulation'),
(20, 'Strategy', 'strategy'),
(21, 'Multiplayer', 'multiplayer');

-- TAGS:

-- Främst för film:
INSERT INTO tags (id, name, handle) VALUES
(1, 'Superheroes', 'superheroes'),
(2, 'Vampires', 'vampires'),
(3, 'Samurai', 'samurai'),
(4, 'Splatter', 'splatter'),
(5, 'Slasher', 'slasher'),
(6, 'Whodunnit', 'whodunnit'),
(7, 'Noir', 'noir'),
(8, 'Neo-Noir', 'neo-noir'),
(9, 'Handdrawn Animation', 'handdrawn-animation'),
(10, 'CG Animation', 'cg-animation'),
(11, 'Stop-Motion', 'stop-motion'),
(12, 'War', 'war'),
(13, 'Western', 'western'),
(14, 'Psychological', 'psychological'),
(15, 'Mockumentary', 'mockumentary'),
(16, 'Found Footage', 'found-footage'),
(17, 'Dinosaurs', 'dinosaurs'),
(18, 'Slapstick', 'slapstick'),
(19, 'Black Comedy', 'black-comedy'),
(20, 'Werewolves', 'werewolves'),
(21, 'Coming of Age', 'coming-of-age'),
(22, 'Teenagers', 'teenagers'),
(23, 'Adolescence', 'adolescence'),
(24, 'Cars', 'cars'),
(25, 'Pirates', 'pirates'),
(26, 'Vikings', 'vikings'),
(27, 'Monsters', 'monsters'),
(28, 'Spies', 'spies'),
(29, 'Post-Apocalypse', 'post-apocalypse'),
(30, 'Dystopian', 'dystopian'),
(31, 'Utopian', 'utopian'),
(32, 'Martial-Arts', 'martial-arts'),
(33, 'Zombies', 'zombies'),
(34, 'Heists', 'heists'),
(35, 'Gore', 'gore'),
(36, 'Aliens', 'aliens'),
(37, 'Ghosts', 'ghosts'),
(38, 'Time Travel', 'time-travel'),
(39, 'School', 'school'),
(40, 'Courtroom', 'courtroom'),
(41, 'Disaster', 'disaster'),
(42, 'History', 'history'),
(43, 'Future', 'future'),
(44, 'Anime', 'anime'),
(45, 'Spaghetti Western', 'spaghetti-western'),
(46, 'Sports', 'sports'),
(47, 'Space', 'space'),
(48, 'Cowboys', 'cowboys'),
(49, 'Nudity', 'nudity'),
(50, 'Racing', 'racing'),
(51, 'Underwater', 'underwater'),
(52, 'Isolation', 'isolation'),
(53, 'Loneliness', 'loneliness'),
(54, 'Love', 'love'),
(55, 'Detectives', 'detectives'),
(56, 'Body Horror', 'body-horror'),
(81, 'Drugs', 'drugs'),
(82, 'Alcohol', 'alcohol'),
(83, 'Violence', 'violence'),
(84, 'Kids', 'kids'),
(85, 'Planes', 'planes'),
(86, 'Boats', 'boats'),
(87, 'Wilderness', 'wilderness'),
(88, 'Exotic Locations', 'exotic-locations'),
(89, 'Medieval', 'medieval'),
(90, 'Knights', 'knights'),
(91, 'Mythology', 'mythology'),
(92, 'Norse Mythology', 'norse-mythology'),
(93, 'Greek Mythology', 'greek-mythology'),
(94, 'Egyptian Mythology', 'egyptian-mythology'),
(95, 'Christian Mythology', 'christian-mythology'),
(96, 'Arthurian', 'arthurian'),
(97, 'Shakespeare', 'shakespeare'),
(98, 'High-School', 'high-school'),
(99, 'College', 'college'),
(100, 'Epic', 'epic');

-- Främst för spel:
INSERT INTO tags (id, name, handle) VALUES
(57, 'Platformer', 'platformer'),
(58, 'Collectathon', 'collectathon'),
(59, 'Metroidvania', 'metroidvania'),
(60, 'Roguelike', 'roguelike'),
(61, 'Cards', 'cards'),
(62, 'Board', 'board'),
(63, '2D', '2d'),
(64, '3D', '3d'),
(65, 'Shooter', 'shooter'),
(66, 'First-Person', 'first-person'),
(67, 'Third-Person', 'third-person'),
(68, 'Sandbox', 'sandbox'),
(69, 'Exploration', 'exploration'),
(70, 'Open-World', 'open-world'),
(71, 'Story-Heavy', 'story-heavy'),
(72, 'MMO', 'mmo'),
(73, 'Competetive', 'competetive'),
(74, 'Casual', 'casual'),
(75, 'Turn-Based', 'turn-based'),
(76, 'Fighting', 'fighting'),
(77, 'Tower Defence', 'tower-defence'),
(78, 'Construction', 'construction'),
(79, 'Randomly Generated', 'randomly-generated'),
(80, 'Party', 'party');

-- ARTIST ROLES:
INSERT INTO artist_roles (id, name, handle) VALUES
(1, 'Actor', 'actor'),
(2, 'Director', 'director'),
(3, 'Writer', 'writer'),
(4, 'Cinematographer', 'cinematographer'),
(5, 'Composer', 'composer'),
(6, 'Producer', 'producer'),
(7, 'Executive Producer', 'executive-producer'),
(8, 'Editor', 'editor'),
(9, 'Production Designer', 'production-designer'),
(10, 'Art Director', 'art-director'),
(11, 'Sound', 'sound'),
(12, 'Costume', 'costume'),
(13, 'Hair', 'hair'),
(14, 'Make-Up', 'make-up'),
(15, 'Author', 'author'),
(16, 'Book Editor', 'book-editor');

-- ARTISTS:
INSERT INTO artists (id, name, handle, bio) VALUES
(1, 'Bryan Cranston', 'bryan-cranston', ''),
(2, 'Bob Odenkirk', 'bob-odenkirk', ''),
(3, 'Aaron Paul', 'aaron-paul', '');

-- COLLECTIONS:
INSERT INTO collections (id, name, handle) VALUES
(1, 'Breakage Collection', 'breakage-collection');

-- ENTS:

SET @desc_1 = "A chemistry teacher diagnosed with inoperable lung cancer turns to manufacturing and selling methamphetamine with a former student in order to secure his family's future.";
SET @desc_2 = "Five high school students meet in Saturday detention and discover how they have a lot more in common than they thought.";
SET @desc_3 = "In town for a job interview, a young woman arrives at her Airbnb late at night only to find that it has been mistakenly double-booked.";
SET @desc_4 = "The trials and tribulations of criminal lawyer Jimmy McGill in the years leading up to his fateful run-in with Walter White and Jesse Pinkman.";
SET @desc_5 = "A young Viking prince is on a quest to avenge his father's murder.";

INSERT INTO ents (id, type, name, handle, date, `desc`, tagline, length) VALUES
(1, 'series', 'Breaking Bad', 'breaking-bad-2008', '2008-01-20', @desc_1, 'Change [the] Equation.', 5),
(2, 'film', 'The Breakfast Club', 'breakfast-club-1985', '1985-07-05', @desc_2, 'They only met once, but it changed their lives forever.', 97),
(3, 'film', 'Barbarian', 'barbarian-2022', '2022-09-09', @desc_3, 'Come for a night. Stay forever.', 103),
(4, 'series', 'Better Call Saul', 'better-call-saul-2015', '2015-02-08', @desc_4, 'Make the Call', 6),
(5, 'film', 'Northman', 'northman-2022', '2022-04-22', @desc_5, 'Conquer your fate.', 137);

-- GENRE ASSOC:
INSERT INTO ents_genres (ent_id, genre_id, number) VALUES
(1, 8, 1),   -- Breaking Bad: Drama
(1, 16, 2),  -- Breaking Bad: Thriller
(1, 6, 3),   -- Breaking Bad: Crime
(2, 8, 1),   -- Breakfast Club: Drama
(3, 11, 1),  -- Barbarian: Horror
(3, 16, 2),  -- Barbarian: Thriller
(3, 13, 3),  -- Barbarian: Mystery
(4, 8, 1),   -- Better Call Saul: Drama
(4, 6, 2),   -- Better Call Saul: Crime
(5, 1, 1),   -- Northman: Action
(5, 16, 2),  -- Northman: Thriller
(5, 8, 3);   -- Northman: Drama

-- CREW ROLE ASSOC:
INSERT INTO ents_artists (ent_id, artist_id, role_id, subrole, number) VALUES
(1, 1, 1, 'Walter White', 1),  -- Breaking Bad: Bryan Cranston as Walter White
(1, 3, 1, 'Jesse Pinkman', 2), -- Breaking Bad: Aaron Paul as Jesse Pinkman
(1, 2, 1, 'Saul Goodman', 3),  -- Breaking Bad: Bob Odenkirk as Saul Goodman
(4, 2, 1, 'Jimmy McGill', 1);  -- Better Call Saul: Bob Odenkirk as Jimmy McGill

-- ENT COLLECTION ASSOCIATIONS:
INSERT INTO ents_collections (ent_id, collection_id) VALUES
(1, 1),  -- Breaking Bad in Breakage Collection
(4, 1);  -- Better Call Saul in Breakage Collection
