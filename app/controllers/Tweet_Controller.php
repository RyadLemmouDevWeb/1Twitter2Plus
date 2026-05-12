<?php
require_once __DIR__ . '/../models/Feed_Model.php';

class TweetController
{
    private $feedModel;

    public function __construct()
    {
        $this->feedModel = new Feed();
    }

    public function showTweetPage($tweetId)
    {
        $viewerId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($viewerId <= 0 || $tweetId <= 0) {
            header('Location: /feed');
            exit();
        }

        $tweet = $this->feedModel->getTweetById((int) $tweetId, $viewerId);
        if (!$tweet) {
            header('Location: /404');
            exit();
        }

        $parentTweet = null;
        if (!empty($tweet['reply_to'])) {
            $parentTweet = $this->feedModel->getTweetById((int) $tweet['reply_to'], $viewerId);
        }

        $replies = $this->feedModel->getTweetReplies((int) $tweetId, $viewerId);

        require __DIR__ . '/../views/Tweet_View.php';
    }

    public function replyToTweet($tweetId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /feed');
            exit();
        }

        if (!csrf_verify()) {
            header('Location: /feed');
            exit();
        }

        $viewerId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($viewerId <= 0 || $tweetId <= 0) {
            header('Location: /feed');
            exit();
        }

        $content = $_POST['content'] ?? '';
        $this->feedModel->createReplyToTweet((int) $tweetId, $viewerId, $content);

        header('Location: /tweet?id=' . (int) $tweetId);
        exit();
    }
}
