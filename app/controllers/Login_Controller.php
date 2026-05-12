<?php
require_once __DIR__ . '/../models/Login_Model.php';

class Login_Controller
{
    private $loginModel;

    public function __construct()
    {
        $this->loginModel = new Login_Model();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ip = $_SERVER['REMOTE_ADDR'];

            if ($this->loginModel->isRateLimited($ip)) {
                header("Location: /login?error=Too+many+attempts.+Please+wait+15+minutes.");
                exit();
            }

            if (!csrf_verify()) {
                header("Location: /login?error=Invalid+session");
                exit();
            }

            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $success = $this->loginModel->checkEmail($email) && $this->loginModel->verifyPassword($email, $password);
                $this->loginModel->recordAttempt($ip, $success);

                if ($success) {
                    $user = $this->loginModel->getUserByEmail($email);
                    $_SESSION['user'] = $user;
                    header("Location: /feed");
                    exit();
                } else {
                    header("Location: /login?error=Invalid+credentials");
                    exit();
                }
            }
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify()) {
                header("Location: /login?error=Invalid+session");
                exit();
            }

            if (isset($_POST['firstname'], $_POST['lastname'], $_POST['display_name'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['birthdate'])) {
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $display_name = $_POST['display_name'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $birthdate = $_POST['birthdate'];
                
                $passwordValidation = validate_password($password);
                if ($passwordValidation !== true) {
                    header("Location: /login?error=" . urlencode($passwordValidation));
                    exit();
                }

                $phone = $_POST['phone'] ?? null;
                $genre = $_POST['genre'] ?? null;
                $picture = $_FILES['picture'] ?? null;
                $header = $_FILES['header'] ?? null;
                $url = $_POST['url'] ?? null;
                $biography = $_POST['biography'] ?? null;
                $city = $_POST['city'] ?? null;
                $country = $_POST['country'] ?? null;

                if ($this->loginModel->registerUser($firstname, $lastname, $display_name, $username, $email, $password, $birthdate, $phone, $genre, $picture, $header, $url, $biography, $city, $country)) {
                    $user = $this->loginModel->getUserByEmail($email);
                    $_SESSION['user'] = $user;
                    header("Location: /feed");
                    exit();
                } else {
                    header("Location: /login?error=Unable+to+register");
                    exit();
                }
            }
        }
    }
}
