<?php

class Feed
{
    private $db;

    public function __construct()
    {
        $this->db = new PDO("mysql:host=localhost;dbname=twitter", "twitter", "root");
    }

    public function getTweetForFeed()
    {
        $query = $this->db->prepare(
            "SELECT user.picture, user.display_name, user.username, tweet.creation_date, tweet.id AS 'id_tweet', tweet.content, tweet.media1, tweet.media2, tweet.media3, tweet.media4 FROM tweet JOIN user ON user.id = tweet.id_user ORDER BY tweet.creation_date DESC LIMIT 100"
            // "SELECT user.picture, user.display_name, user.username, tweet.creation_date, tweet.content, tweet.media1, tweet.media2, tweet.media3, tweet.media4, COUNT(likes.id_user) AS 'like', COUNT(retweet.id_user) AS 'rt' FROM tweet JOIN user ON user.id = tweet.id_user JOIN likes ON tweet.id = likes.id_tweet JOIN retweet ON tweet.id = retweet.id_tweet ORDER BY tweet.creation_date DESC LIMIT 100"
        );
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSuggestUsers()
    {
        $query = $this->db->prepare(
            "SELECT user.id, user.display_name, user.username FROM user ORDER BY user.id LIMIT 4"
        );
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertTweet($content, $media1, $media2, $media3, $media4)
    {
        $userId = $_SESSION['user']['id'];
        $query = $this->db->prepare(
            "INSERT INTO tweet (id_user, content, media1, media2, media3, media4, creation_date) VALUES (:id_user, :content, :media1, :media2, :media3, :media4, :creation_date)"
        );
        $query->execute([
            ':id_user' => $userId,
            ':content' => $content,
            ':media1' => $media1,
            ':media2' => $media2,
            ':media3' => $media3,
            ':media4' => $media4,
            ':creation_date' => date('Y-m-d H:i:s')
        ]);
    }
}