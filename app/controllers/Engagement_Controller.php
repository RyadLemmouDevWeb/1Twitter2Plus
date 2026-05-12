<?php
require_once __DIR__ . '/../models/Feed_Model.php';

class EngagementController
{
    private $feedModel;

    public function __construct()
    {
        $this->feedModel = new Feed();
    }

    public function toggleLike()
    {
        $this->handleToggle('like');
    }

    public function toggleRetweet()
    {
        $this->handleToggle('retweet');
    }

    public function toggleBookmark()
    {
        $this->handleToggle('bookmark');
    }

    private function handleToggle($action)
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

        $tweetId = (int) ($_POST['tweet_id'] ?? 0);
        $viewerId = (int) $_SESSION['user']['id'];

        if ($tweetId > 0) {
            if ($action === 'like') {
                $this->feedModel->toggleLike($tweetId, $viewerId);
            } elseif ($action === 'retweet') {
                $this->feedModel->toggleRetweet($tweetId, $viewerId);
            } elseif ($action === 'bookmark') {
                $this->feedModel->toggleBookmark($tweetId, $viewerId);
            }
        }

        $redirect = $this->getSafeRedirect('/feed');
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
