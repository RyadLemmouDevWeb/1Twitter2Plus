<?php
require_once __DIR__ . '/../models/Login_Model.php';

class Login_Controller
{
    private $loginModel;
    private $salt = "vive le projet tweet_academy";

    public function __construct()
    {
        $this->loginModel = new Login_Model();
        
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $hashedPassword = $this->hashPassword($password);

                if ($this->loginModel->checkEmail($email) && $this->loginModel->verifyPassword($email, $hashedPassword)) {
                    $user = $this->loginModel->getUserByEmail($email);
                    $_SESSION['user'] = $user;
                    header("Location: /feed");
                    exit();
                } else {
                    header("Location: /404");
                    exit();
                }
            }
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['firstname'], $_POST['lastname'], $_POST['display_name'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['birthdate'])) {
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $display_name = $_POST['display_name'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $birthdate = $_POST['birthdate'];
                $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
                $genre = isset($_POST['genre']) ? $_POST['genre'] : null;
                $picture = isset($_FILES['picture']) ? $_FILES['picture'] : null;
                $header = isset($_FILES['header']) ? $_FILES['header'] : null;
                $url = isset($_POST['url']) ? $_POST['url'] : null;
                $biography = isset($_POST['biography']) ? $_POST['biography'] : null;
                $city = isset($_POST['city']) ? $_POST['city'] : null;
                $country = isset($_POST['country']) ? $_POST['country'] : null;
                $ban = isset($_POST['ban']) ? $_POST['ban'] : null;
                $verification_code = isset($_POST['verification_code']) ? $_POST['verification_code'] : null;
                $hashedPassword = $this->hashPassword($password);

                if ($this->loginModel->registerUser($firstname, $lastname, $display_name, $username, $email, $hashedPassword, $birthdate, $phone, $genre, $picture, $header, $url, $biography, $city, $country, $ban, $verification_code)) {
                    $user = $this->loginModel->getUserByEmail($email);
                    $_SESSION['user'] = $user;
                    header("Location: /feed");
                    exit();
                } else {
                    header("Location: /404");
                    exit();
                }
            }
        }
    }

    private function hashPassword($password)
    {
        $passwordWithSalt = $password . $this->salt;
        return hash('ripemd160', $passwordWithSalt);
    }
}