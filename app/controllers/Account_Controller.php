<?php
require_once __DIR__ . '/../models/Account_Model.php';

class AccountController {
    private $model;

    public function __construct() {
        $this->model = new AccountModel();
    }

    public function showAccountPage($userId) {
        if (!$userId || !is_numeric($userId)) {
            header("Location: /404");
            exit();
        }

        $user = $this->model->getUserById($userId);
        if (!$user) {
            header("Location: /404");
            exit();
        }

        $tweets = $this->model->getTweetsByUserId($userId);
        $followersCount = $this->model->getFollowersCount($userId);
        $followingCount = $this->model->getFollowingCount($userId);

        $viewData = [
            'pageTitle' => 'Profil de ' . htmlspecialchars($user['display_name'] ?? $user['firstname']),
            'user' => $user,
            'tweets' => $tweets,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount
        ];

        $this->renderView('Account_View/Account_View', $viewData);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $data['picture'] = $_FILES['picture']['name'] ?? null;

            try {
                $this->model->updateUser($_SESSION['user']['id'], $data);
                header("Location: /account?message=update_success");
                exit();
            } catch (Exception $e) {
                error_log('Error updating user: ' . $e->getMessage());
                header("Location: /account?message=update_failed");
                exit();
            }
        } else {
            header("Location: /404");
            exit();
        }
    }

    public function logout() {
        session_unset(); 
        session_destroy(); 
        header("Location: /login");
        exit();
    }

    private function renderView($viewName, $data) {
        extract($data);
        require __DIR__ . "/../views/Account_View.php";
    }
}
?>
