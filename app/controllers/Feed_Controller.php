<?php
require "../app/models/Feed_Model.php";

class FeedController
{
    private $feedModel;

    public function __construct()
    {
        $this->feedModel = new Feed();
    }

    public function getTweetForFeed()
    {
        return $this->feedModel->getTweetForFeed();
    }

    public function suggestUsers()
    {
        return $this->feedModel->getSuggestUsers();
    }

    public function postTweet()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = $_POST['content'];
            $media1 = $_POST['media1'] ?? null;
            $media2 = $_POST['media2'] ?? null;
            $media3 = $_POST['media3'] ?? null;
            $media4 = $_POST['media4'] ?? null;

            $this->feedModel->insertTweet($content, $media1, $media2, $media3, $media4);
            header("Location: /feed");
        }
    }
}