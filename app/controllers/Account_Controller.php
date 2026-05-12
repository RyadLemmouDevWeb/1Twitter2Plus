<?php
require_once __DIR__ . '/../models/Account_Model.php';
require_once __DIR__ . '/../models/Block_Model.php';

class AccountController {
    private $model;
    private $blockModel;

    public function __construct() {
        $this->model = new AccountModel();
        $this->blockModel = new BlockModel();
    }

    public function showAccountPage($userId, $viewerId = null) {
        if (!$userId || !is_numeric($userId)) {
            header("Location: /404");
            exit();
        }

        $user = $this->model->getUserById($userId);
        if (!$user) {
            header("Location: /404");
            exit();
        }

        $this->renderAccountPage($user, $viewerId);
    }

    public function showAccountPageByUsername($username, $viewerId = null) {
        if (!$username) {
            header("Location: /404");
            exit();
        }

        $user = $this->model->getUserByUsername($username);
        if (!$user) {
            header("Location: /404");
            exit();
        }

        $this->renderAccountPage($user, $viewerId);
    }

    private function renderAccountPage($user, $viewerId = null) {
        $profileId = (int) $user['id'];
        $viewerId = (int) $viewerId;

        $isOwnProfile = $viewerId !== 0 && $viewerId === $profileId;
        $isBlocked = false;
        $hasBlockedMe = false;

        if (!$isOwnProfile && $viewerId !== 0) {
            $isBlocked = $this->blockModel->isBlocked($viewerId, $profileId);
            $blockedIds = $this->blockModel->getBlockedUserIds($viewerId);
            $isBlockedByMe = in_array($profileId, $blockedIds);
            
            $whoBlockedMe = $this->blockModel->getUsersWhoBlockedMe($viewerId);
            $hasBlockedMe = in_array($profileId, $whoBlockedMe);
        } else {
            $isBlockedByMe = false;
        }

        $tweets = [];
        $followersCount = 0;
        $followingCount = 0;
        $isFollowing = false;

        if (!$isBlockedByMe && !$hasBlockedMe) {
            $tweets = $this->model->getTweetsByUserId($profileId);
            $followersCount = $this->model->getFollowersCount($profileId);
            $followingCount = $this->model->getFollowingCount($profileId);
            
            if (!$isOwnProfile && $viewerId !== 0) {
                $isFollowing = $this->model->isFollowing($viewerId, $profileId);
            }
        }

        $viewData = [
            'pageTitle' => 'Profil de ' . htmlspecialchars($user['display_name'] ?? $user['firstname']),
            'user' => $user,
            'tweets' => $tweets,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
            'isOwnProfile' => $isOwnProfile,
            'isFollowing' => $isFollowing,
            'isBlockedByMe' => $isBlockedByMe,
            'hasBlockedMe' => $hasBlockedMe
        ];

        $this->renderView('Account_View', $viewData);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify()) {
                header("Location: /account?message=update_failed");
                exit();
            }

            $data = $_POST;

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
