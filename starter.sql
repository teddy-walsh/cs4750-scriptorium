CREATE TABLE IF NOT EXISTS users(
user_id int AUTO_INCREMENT,
email varchar(100) NOT NULL CHECK (email LIKE '%@%.%'),
display_name varchar(100) NOT NULL,
password text NOT NULL,
    PRIMARY KEY (user_id),
UNIQUE (user_id),
UNIQUE (display_name),
UNIQUE (email)
   );

CREATE TABLE IF NOT EXISTS user_full_names(
    user_id int UNIQUE,
first_name varchar(30) NOT NULL,
middle_name varchar(30),
last_name varchar(30) NOT NULL,
PRIMARY KEY (user_id),
FOREIGN KEY(user_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS userpage (
user_id int,
bio text, 
URL varchar(255),
PRIMARY KEY(user_id),
FOREIGN KEY(user_id) REFERENCES user_full_names(user_id)
);

CREATE TABLE IF NOT EXISTS comments (
comment_id int AUTO_INCREMENT,
user_id int NOT NULL,
FOREIGN KEY (user_id) REFERENCES users(user_id),
comments_text text NOT NULL,
time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY(comment_id)
);

CREATE TABLE IF NOT EXISTS comment_parent (
comment_id_parent int,
comment_id_child int,
FOREIGN KEY (comment_id_parent) REFERENCES comments(comment_id),
FOREIGN KEY (comment_id_child) REFERENCES comments(comment_id), 
PRIMARY KEY (comment_id_child)
);

CREATE TABLE IF NOT EXISTS scripts (
script_id int NOT NULL AUTO_INCREMENT,
title varchar(255) NOT NULL,
blurb text,
script_body MEDIUMTEXT NOT NULL,
datetime DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
genre varchar(255) NOT NULL,
PRIMARY KEY (script_id)
);

CREATE TABLE IF NOT EXISTS script_parent (
script_id_parent int,
comment_id_child int,
FOREIGN KEY (script_id_parent) REFERENCES scripts(script_id) ON DELETE CASCADE,
FOREIGN KEY (comment_id_child) REFERENCES comments(comment_id), 
PRIMARY KEY (comment_id_child)
);

CREATE TABLE IF NOT EXISTS user_created (
user_id int NOT NULL,
FOREIGN KEY (user_id) REFERENCES users(user_id),
script_id int NOT NULL,
FOREIGN KEY (script_id) REFERENCES scripts(script_id),
PRIMARY KEY (user_id, script_id)
);

CREATE TABLE IF NOT EXISTS votes (
vote_id int AUTO_INCREMENT,
user_id int NOT NULL, 
FOREIGN KEY (user_id) REFERENCES users(user_id),
direction int,
PRIMARY KEY(vote_id)
);

CREATE TABLE IF NOT EXISTS votes_on_scripts (
vote_id int,
FOREIGN KEY (vote_id) REFERENCES votes(vote_id),
script_id int,
    FOREIGN KEY (script_id) REFERENCES scripts(script_id),
PRIMARY KEY(vote_id)
);

CREATE TABLE IF NOT EXISTS votes_on_comments (
vote_id int,
FOREIGN KEY (vote_id) REFERENCES votes(vote_id),
comment_id int, 
FOREIGN KEY (comment_id) REFERENCES comments(comment_id),
PRIMARY KEY(vote_id)
);

INSERT INTO users VALUES
(1, "aaa@aaa.com", "aaa", "aaa"), 
(2, "bbb@bbb.com", "bbb", "bbb"), 
(3, "ccc@ccc.com", "ccc", "ccc"), 
(4, "ddd@ddd.com", "ddd", "ddd"), 
(5, "eee@eee.com", "eee", "eee"), 
(6, "fff@fff.com", "fff", "fff"),
(7, "ggg@ggg.com", "ggg", "ggg"), 
(8, "hhh@hhh.com", "hhh", "hhh"), 
(9, "iii@iii.com", "iii", "iii"), 
(10, "jjj@jjj.com", "jjj", "jjj");

INSERT INTO user_full_names VALUES 
(1, "Aaron", "Anders", "Anderson"),
(2, "Benny", "Barry", "Bond"),
(3, "Cathy", "Carol", "Carolson"),
(4, "Dennis", "Darnel", "Davidson"),
(5, "Eric", NULL, "Ericson"),
(6, "Francis", NULL, "Franconia"),
(7, "George", "George", "George"),
(8, "Harry", "H", "Harrison"),
(9, "Ingrid", "Iris", "Inglemann"),
(10, "Jennifer", NULL, "Jensen");

INSERT INTO userpage VALUES
    (1, "Hi, Im Aaron", ""), 
(2, NULL, NULL), 
(3, "CHECK OUT MY INSTA!", "http://instagram.com/myaccount"), 
(4, NULL, NULL), 
(5, NULL, NULL), 
(6, NULL, NULL), 
(7, NULL, NULL), 
(8, NULL, NULL), 
(9, NULL, NULL), 
(10, NULL, NULL);

INSERT INTO scripts (script_id, title, blurb, script_body, datetime, genre) VALUES 
(1, "Script1", "Blurb1", "This is script1", (SELECT NOW()), "Mystery"), 
(2, "Script2", "Blurb2", "This is script2", (SELECT NOW()), "Romantic Comedy"), 
(3, "Script3", "Blurb3", "This is script3", (SELECT NOW()), "Comedy"), 
(4, "Script4", "Blurb4", "This is script4", (SELECT NOW()), "Sci-Fi"), 
(5, "Script5", "Blurb5", "This is script5", (SELECT NOW()), "Fantasy"), 
(6, "Script6", "Blurb6", "This is script6", (SELECT NOW()), "Action"), 
(7, "Script7", "Blurb7", "This is script7", (SELECT NOW()), "Adventure"), 
(8, "Script8", "Blurb8", "This is script8", (SELECT NOW()), "Drama"), 
(9, "Script9", "Blurb9", "This is script9", (SELECT NOW()), "Horror"), 
(10, "Script10", "Blurb10", "This is script10", (SELECT NOW()), "Black Comedy");

INSERT INTO comments (comment_id, user_id, comments_text, time) VALUES
    (1, 1, "hey",  (SELECT NOW())), 
(2, 2, "how is it going", (SELECT NOW())), 
(3, 3, "i love you", (SELECT NOW())), 
(4, 4, "wtf", (SELECT NOW())), 
(5, 5, "eww", (SELECT NOW())), 
(6, 6, "frick", (SELECT NOW())),
(7, 7, "good script", (SELECT NOW())), 
(8, 8, "have a good day", (SELECT NOW())), 
(9, 9, "ilysm", (SELECT NOW())), 
(10, 10, "just kidding", (SELECT NOW()));

INSERT INTO comment_parent (comment_id_child, comment_id_parent) VALUES
    (1, 2), 
    (2, 3),
    (3, 4), 
    (4, 5);

INSERT INTO script_parent(script_id_parent, comment_id_child) VALUES
    (1,5),
    (2,6),
    (3,7),
    (4,8),
    (5,9),
    (6,10);

INSERT INTO user_created(user_id, script_id) VALUES
    (1,1),
    (2,2),
    (3,3),
    (4,4),
    (5,5),
    (6,6),
    (7,7),
    (8,8),
    (9,9),
    (10,10);

INSERT INTO  votes(vote_id, user_id, direction) VALUES
    (1,1,1),
    (2,2,-1),
    (3,3,1),
    (4,4,-1),
    (5,5,1),
    (6,6,-1),
    (7,7,1),
    (8,8,-1),
    (9,9,1),
    (10,10,1),
    (11,1,1);

INSERT INTO votes_on_scripts(vote_id, script_id) VALUES
    (1,1),
    (2,2),
    (3,3),
    (4,4),
    (5,5),
    (6,6);

INSERT INTO votes_on_comments(vote_id, comment_id) VALUES
    (7,1),
    (8,2),
    (9,3),
    (10,4),
    (11,5);

ALTER TABLE `votes_on_scripts` DROP FOREIGN KEY `votes_on_scripts_ibfk_2`; ALTER TABLE `votes_on_scripts` ADD CONSTRAINT `votes_on_scripts_ibfk_2` FOREIGN KEY (`script_id`) REFERENCES `scripts`(`script_id`) ON DELETE CASCADE ON UPDATE RESTRICT; 

DELIMITER $$
CREATE PROCEDURE `count_comment_votes`(IN id int, OUT score INT)
SELECT SUM(direction) INTO score FROM (votes_on_comments INNER JOIN votes ON votes_on_comments.vote_id) WHERE comment_id = id$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `count_script_votes`(IN `id` INT)
SELECT COALESCE(SUM(direction),0) as score FROM (votes_on_scripts NATURAL JOIN votes) WHERE script_id = id$$
DELIMITER ;