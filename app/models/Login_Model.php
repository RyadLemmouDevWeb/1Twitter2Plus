<?php

class Login_Model
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=twitter', 'twitter', 'root');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $hashedPassword = $stmt->fetchColumn();

        return password_verify($password, $hashedPassword);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? $user : null;
    }


    public function registerUser($firstname, $lastname, $display_name, $username, $email, $password, $birthdate, $phone, $genre, $picture, $header)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $profilePicturePath = $this->handleFileUpload($picture, 'picture');
        $headerPicturePath = $this->handleFileUpload($header, 'header');
        $creationDate = date('Y-m-d');
        $stmt = $this->pdo->prepare("INSERT INTO user (firstname, lastname, display_name, username, email, password, birthdate, phone, genre, picture, header, creation_date) 
        VALUES (:firstname, :lastname, :display_name, :username, :email, :password, :birthdate, :phone, :genre, :picture, :header, :creation_date)");

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
            'creation_date' => $creationDate
        ]);

        $userId = $this->pdo->lastInsertId();

        return true;
    }

    private function handleFileUpload($file, $directory)
    {
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/' . $directory . '/'; 
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid() . '.' . $fileExtension;
            $uploadFilePath = $uploadDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                return $newFileName;
            }
        }
        
        return null;
    }
}