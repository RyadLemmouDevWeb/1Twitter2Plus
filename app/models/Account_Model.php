<?php
require_once __DIR__ . '/Database.php';

class AccountModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getUserById($userId) {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getTweetsByUserId($userId) {
        $stmt = $this->db->prepare(
            "SELECT t.*, u.username, u.display_name, u.picture
            FROM tweet t
            JOIN user u ON t.id_user = u.id
            WHERE t.id_user = :userId
            ORDER BY t.creation_date DESC"
        );
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFollowersCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM follow WHERE id_user_followed = :userId");
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getFollowingCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM follow WHERE id_user_follow = :userId");
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function isFollowing($viewerId, $profileId) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM follow WHERE id_user_follow = :viewerId AND id_user_followed = :profileId"
        );
        $stmt->bindValue(':viewerId', $viewerId, PDO::PARAM_INT);
        $stmt->bindValue(':profileId', $profileId, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    public function updateUser($userId, $data) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                UPDATE user SET
                    firstname = :firstname,
                    lastname = :lastname,
                    username = :username,
                    display_name = :display_name,
                    biography = :biography,
                    city = :city,
                    country = :country
                WHERE id = :id
            ");
            $stmt->bindValue(':firstname', $data['firstname'] ?? '');
            $stmt->bindValue(':lastname', $data['lastname'] ?? '');
            $stmt->bindValue(':username', $data['username'] ?? '');
            $stmt->bindValue(':display_name', $data['display_name'] ?? '');
            $stmt->bindValue(':biography', $data['biography'] ?? '');
            $stmt->bindValue(':city', $data['city'] ?? '');
            $stmt->bindValue(':country', $data['country'] ?? '');
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            if (!empty($_FILES['picture']['tmp_name'])) {
                $picturePath = handle_file_upload($_FILES['picture'], 'picture');
                if ($picturePath) {
                    $stmt = $this->db->prepare("UPDATE user SET picture = :picture WHERE id = :id");
                    $stmt->bindValue(':picture', $picturePath);
                    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
                    $stmt->execute();
                    $_SESSION['user']['picture'] = $picturePath;
                }
            }

            if (!empty($_FILES['header']['tmp_name'])) {
                $headerPath = handle_file_upload($_FILES['header'], 'header');
                if ($headerPath) {
                    $stmt = $this->db->prepare("UPDATE user SET header = :header WHERE id = :id");
                    $stmt->bindValue(':header', $headerPath);
                    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
                    $stmt->execute();
                    $_SESSION['user']['header'] = $headerPath;
                }
            }

            $this->db->commit();

            $_SESSION['user']['firstname'] = $data['firstname'] ?? $_SESSION['user']['firstname'];
            $_SESSION['user']['lastname'] = $data['lastname'] ?? $_SESSION['user']['lastname'];
            $_SESSION['user']['username'] = $data['username'] ?? $_SESSION['user']['username'];
            $_SESSION['user']['display_name'] = $data['display_name'] ?? $_SESSION['user']['display_name'];

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Database Update Error: ' . $e->getMessage());
            throw new Exception('Failed to update user');
        }
    }
}
