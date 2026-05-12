<?php
require_once __DIR__ . '/Database.php';

class HashtagModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getTrending($limit = 5)
    {
        $stmt = $this->db->prepare(
            "SELECT h.name, COUNT(th.id_tweet) as count 
             FROM hashtag h
             JOIN tweet_hashtag th ON h.id = th.id_hashtag
             GROUP BY h.id
             ORDER BY count DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
