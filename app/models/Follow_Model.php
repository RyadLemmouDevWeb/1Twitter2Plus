<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Notification_Model.php';

class FollowModel
{
    private $db;
    private $notificationModel;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->notificationModel = new NotificationModel();
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT id, username FROM user WHERE username = :username");
        $stmt->execute(['username' => $username]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function isFollowing($followerId, $followedId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM follow WHERE id_user_follow = :follower AND id_user_followed = :followed"
        );
        $stmt->execute([
            'follower' => $followerId,
            'followed' => $followedId,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function followUser($followerId, $followedId)
    {
        $checkBlock = $this->db->prepare(
            "SELECT COUNT(*) FROM block_user 
            WHERE (id_user = :f AND id_blocked_user = :fd)
            OR (id_user = :fd AND id_blocked_user = :f)"
        );
        $checkBlock->execute(['f' => $followerId, 'fd' => $followedId]);
        if ((int) $checkBlock->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO follow (id_user_follow, id_user_followed)
            VALUES (:follower, :followed)
            ON DUPLICATE KEY UPDATE id_user_follow = id_user_follow"
        );
        if ($stmt->execute([
            'follower' => $followerId,
            'followed' => $followedId,
        ])) {
            $this->notificationModel->addNotification($followerId, $followedId, 'follow');
            return true;
        }
        return false;
    }

    public function unfollowUser($followerId, $followedId)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM follow WHERE id_user_follow = :follower AND id_user_followed = :followed"
        );
        return $stmt->execute([
            'follower' => $followerId,
            'followed' => $followedId,
        ]);
    }
}
