<?php
require_once __DIR__ . '/Database.php';

class SearchModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function searchUsers($query, $viewerId)
    {
        $stmt = $this->db->prepare(
            "SELECT id, username, display_name, picture, biography 
             FROM user 
             WHERE (username LIKE :q OR display_name LIKE :q)
             AND NOT EXISTS (
                SELECT 1 FROM block_user 
                WHERE (id_user = :viewerId AND id_blocked_user = user.id)
                OR (id_user = user.id AND id_blocked_user = :viewerId)
             )
             LIMIT 20"
        );
        $stmt->execute(['q' => "%$query%", 'viewerId' => (int) $viewerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchTweets($query, $viewerId)
    {
        $stmt = $this->db->prepare(
            "SELECT 
                user.picture,
                user.display_name,
                user.username,
                tweet.creation_date,
                tweet.id AS id_tweet,
                tweet.content,
                tweet.media1
             FROM tweet
             JOIN user ON user.id = tweet.id_user
             WHERE tweet.content LIKE :q
             AND NOT EXISTS (
                SELECT 1 FROM block_user 
                WHERE (id_user = :viewerId AND id_blocked_user = tweet.id_user)
                OR (id_user = tweet.id_user AND id_blocked_user = :viewerId)
             )
             ORDER BY tweet.creation_date DESC
             LIMIT 20"
        );
        $stmt->execute(['q' => "%$query%", 'viewerId' => (int) $viewerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
