<?php
require_once __DIR__ . '/Database.php';

class NotificationModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function addNotification($from, $to, $type, $tweetId = null)
    {
        if ($from === $to) return; 

        $stmt = $this->db->prepare(
            "INSERT INTO notification (id_user_from, id_user_to, type, id_tweet) 
             VALUES (:from, :to, :type, :tweetId)"
        );
        $stmt->execute([
            'from' => $from,
            'to' => $to,
            'type' => $type,
            'tweetId' => $tweetId
        ]);
    }

    public function getNotificationsForUser($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT n.*, u.username, u.display_name, u.picture, t.content as tweet_content
             FROM notification n
             JOIN user u ON n.id_user_from = u.id
             LEFT JOIN tweet t ON n.id_tweet = t.id
             WHERE n.id_user_to = :userId
             ORDER BY n.date_creation DESC
             LIMIT 50"
        );
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($userId)
    {
        $stmt = $this->db->prepare("UPDATE notification SET is_read = TRUE WHERE id_user_to = :userId");
        $stmt->execute(['userId' => $userId]);
    }
}
