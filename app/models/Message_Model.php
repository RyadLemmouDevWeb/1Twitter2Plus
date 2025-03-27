<?php

class Message_Model
{
  private $db;
  public function __construct()
  {
    $this->db = new PDO("mysql:host=localhost;dbname=twitter", "twitter", "root");
  }

  public function insertMessage($id_sender, $id_receiver, $content, $media)
  {
      $db = $this->db->prepare("INSERT INTO message (id_sender, id_receiver, content, media, date) VALUES (:id_sender, :id_receiver, :content, :media, :date)");
      $db->execute([
          'id_sender' => $id_sender,
          'id_receiver' => $id_receiver,
          'content' => $content,
          'media' => $media,
          'date' => date('Y-m-d H:i:s')
      ]);
  }

  public function showMessage($id_sender, $id_receiver)
  {
    
    $query = $this->db->prepare(
      'SELECT user.username, user.display_name, user.id AS "id_user", user.picture, message.id AS "id_msg", message.content, message.media, message.date, message.is_viewed FROM message INNER JOIN user ON message.id_sender = user.id WHERE (message.id_sender = :id_sender OR message.id_sender = :id_receiver) AND (message.id_receiver = :id_receiver OR message.id_receiver = :id_sender) ORDER BY message.id ASC'
      // 'SELECT user.username, user.display_name, user.id AS "id_user", user.picture, message.id AS "id_msg", message.content, message.media, message.date, message.is_viewed FROM message INNER JOIN user ON message.id_sender = user.id WHERE (message.id_sender = 3 OR message.id_sender = 2) AND (message.id_receiver = 2 OR message.id_receiver = 3) ORDER BY message.id ASC'
      
      );
      $query->execute([
        'id_sender' => $id_sender,
        'id_receiver' => $id_receiver
        ]);

// return "SELECT message.id, message.content, message.media, message.date, message.id_sender, message.id_receiver FROM message JOIN user ON user.id = message.id_sender WHERE message.id_sender = :id_sender AND message.id_receiver = :id_receiver UNION SELECT message.id, message.content, message.media, message.date, message.id_sender, message.id_receiver FROM message JOIN user ON user.id = message.id_receiver WHERE message.id_sender = :id_receiver AND message.id_receiver = :id_sender ORDER BY id ASC"
// ;
      return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function showDiscusion($id_user){
      
      $query = $this->db->prepare(
        'SELECT id_other, user.username, user.display_name, user.picture AS "URL_Profile", MAX(id_message) AS "id_last", MAX(date) AS "date", ANY_VALUE(content) AS "msg_content" FROM (SELECT message.id_sender AS "id_other", message.id AS "id_message", message.date AS "date", message.content FROM message WHERE message.id_receiver = :id_user UNION SELECT message.id_receiver AS "id_other", message.id AS "id_message", message.date AS "date", message.content FROM message WHERE message.id_sender = :id_user) AS T JOIN user ON id_other = user.id GROUP BY id_other ORDER BY MAX(date) DESC'
        // 'SELECT id_other, user.username, user.display_name, user.picture AS "URL_Profile", MAX(id_message) AS "id_last", MAX(date) AS "date", ANY_VALUE(content) AS "msg_content" FROM (SELECT message.id_sender AS "id_other", message.id AS "id_message", message.date AS "date", message.content FROM message WHERE message.id_receiver = 3 UNION SELECT message.id_receiver AS "id_other", message.id AS "id_message", message.date AS "date", message.content FROM message WHERE message.id_sender = 3) AS T JOIN user ON id_other = user.id GROUP BY id_other ORDER BY MAX(date) DESC'
        
      );
      $query->execute([
        'id_user' => $id_user
        ]);
      return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function idFromUsername($username) {
      $query = $this->db->prepare('SELECT id FROM user WHERE username = :username');
      $query->execute([
        'username' => $username
      ]);  
      return $query->fetch(PDO::FETCH_ASSOC);
    }



}

//        LE BON    SELECT id_other, MAX(id_message) AS "id_last", MAX(date) AS "date_last", ANY_VALUE(content) AS "Message Content" FROM (SELECT message.id_sender AS "id_other", message.id AS "id_message", message.date AS "date", message.content FROM message WHERE message.id_receiver = 3 UNION SELECT message.id_receiver AS "id_other", message.id AS "id_message", message.date AS "date", message.content FROM message WHERE message.id_sender = 3) AS T JOIN user ON id_other = user.id GROUP BY id_other ORDER BY date_last ASC;

// SELECT id_other, user.username, user.picture AS "URL_Profile", MAX(id_message) AS "id_last", MAX(date) AS "date_last", ANY_VALUE(content) AS "Message Content" FROM (SELECT message.id_sender AS "id_other", message.id AS "id_message", message.date AS "date", message.content FROM message WHERE message.id_receiver = 3 UNION SELECT message.id_receiver AS "id_other", message.id AS "id_message", message.date AS "date", message.content FROM message WHERE message.id_sender = 3) AS T JOIN user ON id_other = user.id GROUP BY id_other ORDER BY MAX(date) ASC;

      //  LE BON    SELECT id_other, MAX(id_message), MAX(date), ANY_VALUE(content) FROM message INNER JOIN user ON message.id_sender = user.id WHERE (message.id_sender = 3 OR message.id_sender = 2) AND (message.id_receiver = 2 OR message.id_receiver = ) )
// (SELECT message.id_sender AS "id_other", message.id AS "id_message", message.date AS "date", message.content FROM message WHERE message.id_receiver = 3 UNION SELECT message.id_receiver AS "id_other", message.id AS "id_message", message.date AS "date", message.content FROM message WHERE message.id_sender = 3) AS T GROUP BY id_other ORDER BY MAX(date) ASC;

// SELECT message.id, message.content, message.media, message.date, message.id_sender, message.id_receiver FROM message JOIN user ON user.id = message.id_sender WHERE message.id_sender = 3 AND message.id_receiver = 2 UNION SELECT message.id, message.content, message.media, message.date, message.id_sender, message.id_receiver FROM message JOIN user ON user.id = message.id_receiver WHERE message.id_sender = 2 AND message.id_receiver = 3 ORDER BY id ASC;


//SELECT message.id, message.content, message.media, message.date, message.id_sender, message.id_receiver FROM message JOIN user ON user.id = message.id_sender WHERE message.id_sender = :id_sender AND message.id_receiver = :id_receiver UNION SELECT message.id, message.content, message.media, message.date, message.id_sender, message.id_receiver FROM message JOIN user ON user.id = message.id_receiver WHERE message.id_sender = :id_receiver AND message.id_receiver = :id_sender ORDER BY id ASC

//SELECT message.id, message.content AS "msg_content", message.media AS "URL_media", 

// SELECT username_sender, msg_id,  
// message.id, message.content, message.media, message.date, message.id_sender, message.id_receiver FROM message JOIN user ON user.id = message.id_sender WHERE message.id_sender = 3 AND message.id_receiver = 2 UNION SELECT message.id, message.content, message.media, message.date, message.id_sender, message.id_receiver FROM message JOIN user ON user.id = message.id_receiver WHERE message.id_sender = 2 AND message.id_receiver = 3 ORDER BY id ASC;


//SELECT username.display_name, message.id, message.content, message.date  message INNER JOIN user ON message.id_sender = user.id WHERE (message.id_sender = 3 OR message.id_sender = 2) AND (message.id_receiver = 2 OR message.id_receiver = 3);