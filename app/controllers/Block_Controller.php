<?php
require_once __DIR__ . '/../models/Block_Model.php';
require_once __DIR__ . '/../models/Follow_Model.php';

class BlockController
{
    private $blockModel;
    private $followModel;

    public function __construct()
    {
        $this->blockModel = new BlockModel();
        $this->followModel = new FollowModel();
    }

    public function block()
    {
        $this->handleBlockAction(true);
    }

    public function unblock()
    {
        $this->handleBlockAction(false);
    }

    private function handleBlockAction($shouldBlock)
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

        $userIdToBlock = (int) ($_POST['user_id'] ?? 0);
        if ($userIdToBlock <= 0) {
            header('Location: /feed');
            exit();
        }

        $viewerId = (int) $_SESSION['user']['id'];

        if ($viewerId !== $userIdToBlock) {
            if ($shouldBlock) {
                $this->blockModel->blockUser($viewerId, $userIdToBlock);
                $this->followModel->unfollowUser($viewerId, $userIdToBlock);
                $this->followModel->unfollowUser($userIdToBlock, $viewerId);
            } else {
                $this->blockModel->unblockUser($viewerId, $userIdToBlock);
            }
        }

        $redirect = $_POST['redirect'] ?? '/feed';
        header('Location: ' . $redirect);
        exit();
    }
}
