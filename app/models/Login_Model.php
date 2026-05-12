<?php
require_once __DIR__ . '/Database.php';

class Login_Model
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function checkEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function verifyPassword($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT password FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $storedPassword = $stmt->fetchColumn();

        if (!$storedPassword) {
            return false;
        }

        return password_verify($password, $storedPassword);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? $user : null;
    }


    public function registerUser(
        $firstname,
        $lastname,
        $display_name,
        $username,
        $email,
        $password,
        $birthdate,
        $phone,
        $genre,
        $picture,
        $header,
        $url = null,
        $biography = null,
        $city = null,
        $country = null,
        $ban = null,
        $verification_code = null
    )
    {
        if (validate_password($password) !== true) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $profilePicturePath = $this->handleFileUpload($picture, 'picture');
        $headerPicturePath = $this->handleFileUpload($header, 'header');
        
        $creationDate = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare(
            "INSERT INTO user (firstname, lastname, display_name, username, email, password, birthdate, phone, genre, picture, header, url, biography, city, country, ban, verification_code, creation_date)
            VALUES (:firstname, :lastname, :display_name, :username, :email, :password, :birthdate, :phone, :genre, :picture, :header, :url, :biography, :city, :country, :ban, :verification_code, :creation_date)"
        );

        try {
            $stmt->execute([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'display_name' => $display_name,
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'birthdate' => $birthdate,
                'phone' => $phone,
                'genre' => $genre,
                'picture' => $profilePicturePath,
                'header' => $headerPicturePath,
                'url' => $url,
                'biography' => $biography,
                'city' => $city,
                'country' => $country,
                'ban' => $ban,
                'verification_code' => $verification_code,
                'creation_date' => $creationDate,
            ]);

            return true;
        } catch (PDOException $exception) {
            error_log('Register user failed: ' . $exception->getMessage());
            return false;
        }
    }

    public function isRateLimited($ip, $limit = 5, $minutes = 15)
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM login_attempts 
             WHERE ip_address = :ip 
             AND attempt_time > DATE_SUB(NOW(), INTERVAL :minutes MINUTE)
             AND success = FALSE"
        );
        $stmt->execute(['ip' => $ip, 'minutes' => $minutes]);
        return (int) $stmt->fetchColumn() >= $limit;
    }

    public function recordAttempt($ip, $success)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO login_attempts (ip_address, success) VALUES (:ip, :success)"
        );
        $stmt->execute(['ip' => $ip, 'success' => (int)$success]);
    }

    private function handleFileUpload($file, $directory)
    {
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            if (validate_image_upload($file) !== true) {
                return null;
            }

            $uploadDir = __DIR__ . '/../../public/uploads/' . $directory . '/'; 

            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                error_log("Failed to create directory: " . $uploadDir);
                return null;
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);
            $extensions = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif'
            ];
            $extension = $extensions[$mimeType] ?? 'jpg';

            $newFileName = uniqid() . '.' . $extension;
            $uploadFilePath = $uploadDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                return '/uploads/' . $directory . '/' . $newFileName;
            }
        }
        
        return null;
    }
}
