<?php
require_once __DIR__ . '/Database.php';

class BookmarkModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getBookmarksForUser($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT 
                user.picture,
                user.display_name,
                user.username,
                tweet.creation_date,
                tweet.id AS id_tweet,
                tweet.content,
                tweet.media1,
                tweet.media2,
                (SELECT COUNT(*) FROM likes WHERE id_tweet = tweet.id) as likes_count,
                (SELECT COUNT(*) FROM retweet WHERE id_tweet = tweet.id) as retweets_count,
                (SELECT COUNT(*) FROM tweet WHERE reply_to = tweet.id) as replies_count,
                1 as is_bookmarked,
                EXISTS(SELECT 1 FROM likes WHERE id_tweet = tweet.id AND id_user = :userId) as is_liked,
                EXISTS(SELECT 1 FROM retweet WHERE id_tweet = tweet.id AND id_user = :userId) as is_retweeted
             FROM bookmark
             JOIN tweet ON bookmark.id_tweet = tweet.id
             JOIN user ON tweet.id_user = user.id
             WHERE bookmark.id_user = :userId
             AND NOT EXISTS (
                SELECT 1 FROM block_user 
                WHERE (id_user = :userId AND id_blocked_user = user.id)
                OR (id_user = user.id AND id_blocked_user = :userId)
             )
             ORDER BY tweet.creation_date DESC"
        );
        $stmt->execute(['userId' => (int) $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
