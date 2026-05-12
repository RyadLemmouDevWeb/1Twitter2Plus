<?php
require_once __DIR__ . '/../models/Follow_Model.php';

class FollowController
{
    private $model;

    public function __construct()
    {
        $this->model = new FollowModel();
    }

    public function follow()
    {
        $this->handleFollowAction(true);
    }

    public function unfollow()
    {
        $this->handleFollowAction(false);
    }

    private function handleFollowAction($shouldFollow)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /feed');
            exit();
        }

        if (!csrf_verify()) {
            header('Location: /feed');
            exit();
        }

        if (empty($_SESSION['user']['id'])) {
            header('Location: /login');
            exit();
        }

        $username = trim($_POST['username'] ?? '');
        if ($username === '') {
            header('Location: /feed');
            exit();
        }

        $targetUser = $this->model->getUserByUsername($username);
        if (!$targetUser || empty($targetUser['id'])) {
            header('Location: /feed');
            exit();
        }

        $followerId = (int) $_SESSION['user']['id'];
        $followedId = (int) $targetUser['id'];

        if ($followerId !== $followedId) {
            if ($shouldFollow) {
                $this->model->followUser($followerId, $followedId);
            } else {
                $this->model->unfollowUser($followerId, $followedId);
            }
        }

        $defaultRedirect = '/account?username=' . urlencode($username);
        $redirect = $this->getSafeRedirect($defaultRedirect);

        header('Location: ' . $redirect);
        exit();
    }

    private function getSafeRedirect($defaultRedirect)
    {
        $redirect = $_POST['redirect'] ?? '';

        if (!is_string($redirect) || $redirect === '') {
            return $defaultRedirect;
        }

        if ($redirect[0] !== '/' || strpos($redirect, '://') !== false || strpos($redirect, "\n") !== false) {
            return $defaultRedirect;
        }

        return $redirect;
    }
}
