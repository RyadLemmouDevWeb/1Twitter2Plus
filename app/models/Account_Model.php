<?php
class AccountModel {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO(
                'mysql:host=localhost;dbname=twitter;charset=utf8',
                'twitter',  
                'root',  
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
            throw new Exception('Database connection failed');
        }
    }

    public function getUserById($userId) {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: null;
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
        return $stmt->fetchAll();
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

    public function updateUser($userId, $data) {
        try {
            $stmt = $this->db->prepare("
                UPDATE user SET
                    firstname = :firstname,
                    lastname = :lastname,
                    username = :username,
                    biography = :biography,
                    city = :city,
                    country = :country
                WHERE id = :id
            ");
            $stmt->bindValue(':firstname', $data['firstname']);
            $stmt->bindValue(':lastname', $data['lastname']);
            $stmt->bindValue(':username', $data['username']);
            $stmt->bindValue(':biography', $data['biography']);
            $stmt->bindValue(':city', $data['city']);
            $stmt->bindValue(':country', $data['country']);
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            if (!empty($_FILES['picture']['tmp_name'])) {
                $targetDir = 'path/to/uploads/'; // Remplacez par le chemin réel
                $targetFile = $targetDir . basename($_FILES['picture']['name']);
                if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetFile)) {
                    $stmt = $this->db->prepare("UPDATE user SET picture = :picture WHERE id = :id");
                    $stmt->bindValue(':picture', $targetFile);
                    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    error_log('Failed to move uploaded file.');
                }
            }
        } catch (Exception $e) {
            error_log('Database Update Error: ' . $e->getMessage());
            throw new Exception('Failed to update user');
        }
    }
}
?>
