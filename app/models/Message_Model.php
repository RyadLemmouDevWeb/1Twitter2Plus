<?php
require_once __DIR__ . '/Database.php';

class Message_Model
{
  private $db;
  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  public function insertMessage($id_sender, $id_receiver, $content, $media)
  {
      $checkBlock = $this->db->prepare(
          "SELECT COUNT(*) FROM block_user 
          WHERE (id_user = :s AND id_blocked_user = :r)
          OR (id_user = :r AND id_blocked_user = :s)"
      );
      $checkBlock->execute(['s' => $id_sender, 'r' => $id_receiver]);
      if ((int) $checkBlock->fetchColumn() > 0) {
          return false;
      }

      $db = $this->db->prepare("INSERT INTO message (id_sender, id_receiver, content, media, date) VALUES (:id_sender, :id_receiver, :content, :media, :date)");
      return $db->execute([
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
      'SELECT user.username, user.display_name, user.id AS "id_user", user.picture AS "URL_Profile", message.id AS "id_msg", message.content, message.media, message.date, message.is_viewed 
       FROM message 
       INNER JOIN user ON message.id_sender = user.id 
       WHERE (message.id_sender = :id_sender OR message.id_sender = :id_receiver) 
       AND (message.id_receiver = :id_receiver OR message.id_receiver = :id_sender) 
       ORDER BY message.id ASC'
      );
      $query->execute([
        'id_sender' => $id_sender,
        'id_receiver' => $id_receiver
        ]);

      return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function showDiscusion($id_user){
      $query = $this->db->prepare(
        'SELECT u.id AS id_other, u.username, u.display_name, u.picture AS URL_Profile, 
                m.id AS id_last, m.date AS date, m.content AS msg_content
         FROM message m
         JOIN user u ON (m.id_sender = u.id OR m.id_receiver = u.id) AND u.id != :id_user
         WHERE (m.id_sender = :id_user OR m.id_receiver = :id_user)
         AND m.id IN (
             SELECT MAX(id) FROM message 
             WHERE id_sender = :id_user OR id_receiver = :id_user
             GROUP BY IF(id_sender = :id_user, id_receiver, id_sender)
         )
         AND NOT EXISTS (
             SELECT 1 FROM block_user 
             WHERE (id_user = :id_user AND id_blocked_user = u.id)
             OR (id_user = u.id AND id_blocked_user = :id_user)
         )
         ORDER BY m.date DESC'
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
