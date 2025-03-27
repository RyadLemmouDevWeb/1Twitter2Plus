ALTER TABLE `message` CHANGE `id` `id` INT NOT NULL AUTO_INCREMENT;

INSERT INTO user (role, firstname, lastname, username, display_name, email, password, birthdate, creation_date) VALUE
  ("admin", "Tom", "Cardonnel", "Tomu", "umoT", "tom.cardonnel@epitech.eu", "admin", NOW(), CURRENT_TIMESTAMP()),
  ("admin", "Ryad", "Lemmou", "Ryad", "RyadLeBG", "ryad@epitech.eu", "admin", NOW(), CURRENT_TIMESTAMP()),
  ("admin", "Pavel", "Gatti", "Pavel", "Grokk75", "Pavel@epitech.eu", "admin", NOW(), CURRENT_TIMESTAMP()),
  ("admin", "Matheo", "Ritou", "Mateo", "Ratheo", "matheo@epitech.eu", "admin", NOW(), CURRENT_TIMESTAMP());

INSERT INTO tweet (id_user, content, creation_date) VALUE 
  (1, "Hello! Je suis le tweet de Tom.", CURRENT_TIMESTAMP()),
  (2, "Hello! Je suis le tweet de Ryad.", CURRENT_TIMESTAMP()),
  (3, "Hello! Je suis le tweet de Pavel.", CURRENT_TIMESTAMP()),
  (4, "Hello! Je suis le tweet de Rathéo.", CURRENT_TIMESTAMP());


INSERT INTO tweet (id_user, content, media1, creation_date) VALUE 
  (1, "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Architecto dolorum odit quisquam porro eum laborum modi magnam, excepturi error.", "https://cdn.pixabay.com/photo/2018/08/04/11/30/draw-3583548_1280.png", CURRENT_TIMESTAMP()),
  (3, "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Architecto dolorum odit quisquam porro eum laborum modi magnam, excepturi error.", "https://img-cdn.pixlr.com/image-generator/history/65bb506dcb310754719cf81f/ede935de-1138-4f66-8ed7-44bd16efc709/medium.webp", CURRENT_TIMESTAMP()),
  (4, "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Architecto dolorum odit quisquam porro eum laborum modi magnam, excepturi error.", "https://pe-images.s3.amazonaws.com/basics/cc/image-size-resolution/resize-images-for-print/image-cropped-8x10.jpg", CURRENT_TIMESTAMP());

INSERT INTO message (content, id_sender, id_receiver, date) VALUE 
  ("truc machin chose de Pavel a Ryad", 3, 2, '1970-01-01 00:00:00'),
  ("truc machin chose de Pavel a Ryad", 3, 2, '1970-01-02 00:00:00'),
  ("menfou c'est pas interésent", 2, 3, '1970-01-03 00:00:00'),
  ("Je sais qu'e tout le monde s'en fout", 3, 2, '1970-01-04 00:00:00'),
  ("mdr", 2, 3, '1970-01-05 00:00:00'),
  ("CE MESSAGE EST POUR TOM", 3, 1, '1970-01-06 00:00:00'),
  ("Ouais c'est Greg, nan Tom", 1, 3, '1970-01-07 00:00:00' ),
  ("NAAAAAAN", 3, 1, CURRENT_TIMESTAMP()),
  ("Ratheo Mitou Arrive !", 4, 3, '1970-01-08 00:00:00');

  -- SELECT message.id_sender AS "user" FROM message JOIN user ON message.id_receiver = user.id WHERE message.id_receiver = 3 UNION SELECT message.id_receiver AS "User Conv" FROM message JOIN user ON message.id_sender = user.id WHERE message.id_sender = 3;
  -- SELECT message.id_sender AS "user" FROM message WHERE message.id_receiver = 3 UNION SELECT message.id_receiver AS "User Conv" FROM message WHERE message.id_sender = 3;
  -- SELECT id_me, id_other FROM (SELECT message.id_sender AS "id_other" FROM message WHERE message.id_receiver = 3 UNION SELECT message.id_receiver AS "id_other" FROM message WHERE message.id_sender = 3);
  -- SELECT id_other FROM (SELECT message.id_sender AS "id_other" FROM message WHERE message.id_receiver = 3 UNION SELECT message.id_receiver AS "id_other" FROM message WHERE message.id_sender = 3);
-- INSERT INTO likes (id_user, id_tweet) VALUE 
-- (3,5),
-- (2,5),
-- (4,5),
-- (3,8),
-- (4,8);

-- INSERT INTO retweet (id_user, id_tweet) VALUE 
-- (3,5),
-- (2,5),
-- (4,5),
-- (3,4),
-- (4,8);

