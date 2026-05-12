<?php
require_once __DIR__ . '/../models/Feed_Model.php';
require_once __DIR__ . '/../models/Hashtag_Model.php';

class FeedController
{
    private $feedModel;
    private $hashtagModel;

    public function __construct()
    {
        $this->feedModel = new Feed();
        $this->hashtagModel = new HashtagModel();
    }

    public function getTweetForFeed()
    {
        $viewerId = (int) ($_SESSION['user']['id'] ?? 0);
        return $this->feedModel->getTweetForFeed($viewerId, $this->getCurrentTab());
    }

    public function suggestUsers()
    {
        $viewerId = (int) ($_SESSION['user']['id'] ?? 0);
        return $this->feedModel->getSuggestUsers($viewerId);
    }

    public function getTrends()
    {
        return $this->hashtagModel->getTrending(5);
    }

    public function getCurrentTab()
    {
        $tab = $_GET['tab'] ?? 'for-you';

        if ($tab !== 'for-you' && $tab !== 'following') {
            return 'for-you';
        }

        return $tab;
    }

    public function postTweet()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify()) {
                header('Location: /feed');
                exit();
            }

            $content = $_POST['content'] ?? '';

            $media1 = $_POST['media1'] ?? null;
            $media2 = $_POST['media2'] ?? null;
            $media3 = $_POST['media3'] ?? null;
            $media4 = $_POST['media4'] ?? null;

            if (!empty($_FILES['local_media1']['tmp_name'])) {
                $path = handle_file_upload($_FILES['local_media1'], 'tweets');
                if ($path) $media1 = $path;
            }
            if (!empty($_FILES['local_media2']['tmp_name'])) {
                $path = handle_file_upload($_FILES['local_media2'], 'tweets');
                if ($path) $media2 = $path;
            }
            if (!empty($_FILES['local_media3']['tmp_name'])) {
                $path = handle_file_upload($_FILES['local_media3'], 'tweets');
                if ($path) $media3 = $path;
            }
            if (!empty($_FILES['local_media4']['tmp_name'])) {
                $path = handle_file_upload($_FILES['local_media4'], 'tweets');
                if ($path) $media4 = $path;
            }

            $this->feedModel->insertTweet($content, $media1, $media2, $media3, $media4);
        }
    }
}
