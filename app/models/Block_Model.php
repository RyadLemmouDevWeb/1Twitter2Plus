<?php
require_once __DIR__ . '/Database.php';

class BlockModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function blockUser($userId, $blockedUserId)
    {
        if ($userId === $blockedUserId) return false;

        $stmt = $this->db->prepare(
            "INSERT INTO block_user (id_user, id_blocked_user)
            VALUES (:userId, :blockedUserId)
            ON DUPLICATE KEY UPDATE id_user = id_user"
        );
        return $stmt->execute([
            'userId' => (int) $userId,
            'blockedUserId' => (int) $blockedUserId,
        ]);
    }

    public function unblockUser($userId, $blockedUserId)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM block_user WHERE id_user = :userId AND id_blocked_user = :blockedUserId"
        );
        return $stmt->execute([
            'userId' => (int) $userId,
            'blockedUserId' => (int) $blockedUserId,
        ]);
    }

    public function isBlocked($userId, $otherUserId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM block_user 
            WHERE (id_user = :userId AND id_blocked_user = :otherUserId)
            OR (id_user = :otherUserId AND id_blocked_user = :userId)"
        );
        $stmt->execute([
            'userId' => (int) $userId,
            'otherUserId' => (int) $otherUserId,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function getBlockedUserIds($userId)
    {
        $stmt = $this->db->prepare("SELECT id_blocked_user FROM block_user WHERE id_user = :userId");
        $stmt->execute(['userId' => (int) $userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getUsersWhoBlockedMe($userId)
    {
        $stmt = $this->db->prepare("SELECT id_user FROM block_user WHERE id_blocked_user = :userId");
        $stmt->execute(['userId' => (int) $userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}