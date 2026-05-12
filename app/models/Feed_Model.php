<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Notification_Model.php';

class Feed
{
    private $db;
    private $notificationModel;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->notificationModel = new NotificationModel();
    }

    public function getTweetForFeed($viewerId, $tab = 'for-you')
    {
        if ($viewerId <= 0) {
            return [];
        }

        if ($tab === 'following') {
            return $this->getTweetForFollowing((int) $viewerId);
        }

        return $this->getTweetForEveryone((int) $viewerId);
    }

    private function getTweetForEveryone($viewerId)
    {
        $query = $this->db->prepare(
            "SELECT
                user.picture,
                user.display_name,
                user.username,
                tweet.creation_date,
                tweet.id AS id_tweet,
                tweet.content,
                tweet.reply_to,
                tweet.media1,
                tweet.media2,
                tweet.media3,
                tweet.media4,
                COALESCE(replies_count.total, 0) AS replies_count,
                COALESCE(likes_count.total, 0) AS likes_count,
                COALESCE(retweets_count.total, 0) AS retweets_count,
                COALESCE(bookmarks_count.total, 0) AS bookmarks_count,
                IF(user_like.id_user IS NULL, 0, 1) AS is_liked,
                IF(user_retweet.id_user IS NULL, 0, 1) AS is_retweeted,
                IF(user_bookmark.id_user IS NULL, 0, 1) AS is_bookmarked
            FROM tweet
            JOIN user ON user.id = tweet.id_user
            LEFT JOIN (
                SELECT reply_to AS id_tweet, COUNT(*) AS total
                FROM tweet
                WHERE reply_to IS NOT NULL
                GROUP BY reply_to
            ) AS replies_count ON replies_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM likes
                GROUP BY id_tweet
            ) AS likes_count ON likes_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM retweet
                GROUP BY id_tweet
            ) AS retweets_count ON retweets_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM bookmark
                GROUP BY id_tweet
            ) AS bookmarks_count ON bookmarks_count.id_tweet = tweet.id
            LEFT JOIN likes AS user_like ON user_like.id_tweet = tweet.id AND user_like.id_user = :viewerLike
            LEFT JOIN retweet AS user_retweet ON user_retweet.id_tweet = tweet.id AND user_retweet.id_user = :viewerRetweet
            LEFT JOIN bookmark AS user_bookmark ON user_bookmark.id_tweet = tweet.id AND user_bookmark.id_user = :viewerBookmark
            WHERE tweet.reply_to IS NULL
            AND NOT EXISTS (
                SELECT 1 FROM block_user 
                WHERE (id_user = :viewerId AND id_blocked_user = tweet.id_user)
                OR (id_user = tweet.id_user AND id_blocked_user = :viewerId)
            )
            ORDER BY tweet.creation_date DESC
            LIMIT 100"
        );
        $query->execute([
            'viewerLike' => $viewerId,
            'viewerRetweet' => $viewerId,
            'viewerBookmark' => $viewerId,
            'viewerId' => $viewerId,
        ]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getTweetForFollowing($viewerId)
    {
        $query = $this->db->prepare(
            "SELECT
                user.picture,
                user.display_name,
                user.username,
                tweet.creation_date,
                tweet.id AS id_tweet,
                tweet.content,
                tweet.reply_to,
                tweet.media1,
                tweet.media2,
                tweet.media3,
                tweet.media4,
                COALESCE(replies_count.total, 0) AS replies_count,
                COALESCE(likes_count.total, 0) AS likes_count,
                COALESCE(retweets_count.total, 0) AS retweets_count,
                COALESCE(bookmarks_count.total, 0) AS bookmarks_count,
                IF(user_like.id_user IS NULL, 0, 1) AS is_liked,
                IF(user_retweet.id_user IS NULL, 0, 1) AS is_retweeted,
                IF(user_bookmark.id_user IS NULL, 0, 1) AS is_bookmarked
            FROM tweet
            JOIN user ON user.id = tweet.id_user
            LEFT JOIN follow ON follow.id_user_followed = tweet.id_user AND follow.id_user_follow = :viewerFollow
            LEFT JOIN (
                SELECT reply_to AS id_tweet, COUNT(*) AS total
                FROM tweet
                WHERE reply_to IS NOT NULL
                GROUP BY reply_to
            ) AS replies_count ON replies_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM likes
                GROUP BY id_tweet
            ) AS likes_count ON likes_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM retweet
                GROUP BY id_tweet
            ) AS retweets_count ON retweets_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM bookmark
                GROUP BY id_tweet
            ) AS bookmarks_count ON bookmarks_count.id_tweet = tweet.id
            LEFT JOIN likes AS user_like ON user_like.id_tweet = tweet.id AND user_like.id_user = :viewerLike
            LEFT JOIN retweet AS user_retweet ON user_retweet.id_tweet = tweet.id AND user_retweet.id_user = :viewerRetweet
            LEFT JOIN bookmark AS user_bookmark ON user_bookmark.id_tweet = tweet.id AND user_bookmark.id_user = :viewerBookmark
            WHERE tweet.reply_to IS NULL
            AND (tweet.id_user = :viewerOwn OR follow.id_user_followed IS NOT NULL)
            AND NOT EXISTS (
                SELECT 1 FROM block_user 
                WHERE (id_user = :viewerId AND id_blocked_user = tweet.id_user)
                OR (id_user = tweet.id_user AND id_blocked_user = :viewerId)
            )
            ORDER BY tweet.creation_date DESC
            LIMIT 100"
        );
        $query->execute([
            'viewerFollow' => $viewerId,
            'viewerLike' => $viewerId,
            'viewerRetweet' => $viewerId,
            'viewerBookmark' => $viewerId,
            'viewerOwn' => $viewerId,
            'viewerId' => $viewerId,
        ]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSuggestUsers($viewerId)
    {
        $query = $this->db->prepare(
            "SELECT user.id, user.display_name, user.username
            FROM user
            LEFT JOIN follow ON follow.id_user_followed = user.id AND follow.id_user_follow = :viewerId
            WHERE user.id <> :viewerId AND follow.id_user_followed IS NULL
            AND NOT EXISTS (
                SELECT 1 FROM block_user 
                WHERE (id_user = :viewerId AND id_blocked_user = user.id)
                OR (id_user = user.id AND id_blocked_user = :viewerId)
            )
            ORDER BY user.id DESC
            LIMIT 4"
        );
        $query->execute(['viewerId' => $viewerId]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurrentUserFollowingIds($viewerId)
    {
        $query = $this->db->prepare(
            "SELECT id_user_followed FROM follow WHERE id_user_follow = :viewerId"
        );
        $query->execute(['viewerId' => $viewerId]);

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($row) {
            return (int) $row['id_user_followed'];
        }, $rows);
    }

    public function getTweetById($tweetId, $viewerId)
    {
        $query = $this->db->prepare(
            "SELECT
                user.picture,
                user.display_name,
                user.username,
                tweet.creation_date,
                tweet.id AS id_tweet,
                tweet.content,
                tweet.reply_to,
                tweet.media1,
                tweet.media2,
                tweet.media3,
                tweet.media4,
                COALESCE(replies_count.total, 0) AS replies_count,
                COALESCE(likes_count.total, 0) AS likes_count,
                COALESCE(retweets_count.total, 0) AS retweets_count,
                COALESCE(bookmarks_count.total, 0) AS bookmarks_count,
                IF(user_like.id_user IS NULL, 0, 1) AS is_liked,
                IF(user_retweet.id_user IS NULL, 0, 1) AS is_retweeted,
                IF(user_bookmark.id_user IS NULL, 0, 1) AS is_bookmarked
            FROM tweet
            JOIN user ON user.id = tweet.id_user
            LEFT JOIN (
                SELECT reply_to AS id_tweet, COUNT(*) AS total
                FROM tweet
                WHERE reply_to IS NOT NULL
                GROUP BY reply_to
            ) AS replies_count ON replies_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM likes
                GROUP BY id_tweet
            ) AS likes_count ON likes_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM retweet
                GROUP BY id_tweet
            ) AS retweets_count ON retweets_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM bookmark
                GROUP BY id_tweet
            ) AS bookmarks_count ON bookmarks_count.id_tweet = tweet.id
            LEFT JOIN likes AS user_like ON user_like.id_tweet = tweet.id AND user_like.id_user = :viewerLike
            LEFT JOIN retweet AS user_retweet ON user_retweet.id_tweet = tweet.id AND user_retweet.id_user = :viewerRetweet
            LEFT JOIN bookmark AS user_bookmark ON user_bookmark.id_tweet = tweet.id AND user_bookmark.id_user = :viewerBookmark
            WHERE tweet.id = :tweetId
            AND NOT EXISTS (
                SELECT 1 FROM block_user 
                WHERE (id_user = :viewerId AND id_blocked_user = tweet.id_user)
                OR (id_user = tweet.id_user AND id_blocked_user = :viewerId)
            )
            LIMIT 1"
        );
        $query->execute([
            'viewerLike' => $viewerId,
            'viewerRetweet' => $viewerId,
            'viewerBookmark' => $viewerId,
            'tweetId' => $tweetId,
            'viewerId' => $viewerId,
        ]);

        $tweet = $query->fetch(PDO::FETCH_ASSOC);
        return $tweet ?: null;
    }

    public function getTweetReplies($tweetId, $viewerId)
    {
        $query = $this->db->prepare(
            "SELECT
                user.picture,
                user.display_name,
                user.username,
                tweet.creation_date,
                tweet.id AS id_tweet,
                tweet.content,
                tweet.reply_to,
                tweet.media1,
                tweet.media2,
                tweet.media3,
                tweet.media4,
                COALESCE(replies_count.total, 0) AS replies_count,
                COALESCE(likes_count.total, 0) AS likes_count,
                COALESCE(retweets_count.total, 0) AS retweets_count,
                COALESCE(bookmarks_count.total, 0) AS bookmarks_count,
                IF(user_like.id_user IS NULL, 0, 1) AS is_liked,
                IF(user_retweet.id_user IS NULL, 0, 1) AS is_retweeted,
                IF(user_bookmark.id_user IS NULL, 0, 1) AS is_bookmarked
            FROM tweet
            JOIN user ON user.id = tweet.id_user
            LEFT JOIN (
                SELECT reply_to AS id_tweet, COUNT(*) AS total
                FROM tweet
                WHERE reply_to IS NOT NULL
                GROUP BY reply_to
            ) AS replies_count ON replies_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM likes
                GROUP BY id_tweet
            ) AS likes_count ON likes_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM retweet
                GROUP BY id_tweet
            ) AS retweets_count ON retweets_count.id_tweet = tweet.id
            LEFT JOIN (
                SELECT id_tweet, COUNT(*) AS total
                FROM bookmark
                GROUP BY id_tweet
            ) AS bookmarks_count ON bookmarks_count.id_tweet = tweet.id
            LEFT JOIN likes AS user_like ON user_like.id_tweet = tweet.id AND user_like.id_user = :viewerLike
            LEFT JOIN retweet AS user_retweet ON user_retweet.id_tweet = tweet.id AND user_retweet.id_user = :viewerRetweet
            LEFT JOIN bookmark AS user_bookmark ON user_bookmark.id_tweet = tweet.id AND user_bookmark.id_user = :viewerBookmark
            WHERE tweet.reply_to = :tweetId
            AND NOT EXISTS (
                SELECT 1 FROM block_user 
                WHERE (id_user = :viewerId AND id_blocked_user = tweet.id_user)
                OR (id_user = tweet.id_user AND id_blocked_user = :viewerId)
            )
            ORDER BY tweet.creation_date ASC"
        );
        $query->execute([
            'viewerLike' => $viewerId,
            'viewerRetweet' => $viewerId,
            'viewerBookmark' => $viewerId,
            'tweetId' => $tweetId,
            'viewerId' => $viewerId,
        ]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createReplyToTweet($tweetId, $viewerId, $content)
    {
        $trimmedContent = trim((string) $content);
        if ($trimmedContent === '' || $tweetId <= 0 || $viewerId <= 0) {
            return false;
        }

        $parentTweet = $this->getTweetById($tweetId, $viewerId);
        if (!$parentTweet) {
            return false;
        }

        $query = $this->db->prepare(
            "INSERT INTO tweet (id_user, content, reply_to, creation_date) VALUES (:id_user, :content, :reply_to, :creation_date)"
        );
        if ($query->execute([
            ':id_user' => $viewerId,
            ':content' => $trimmedContent,
            ':reply_to' => $tweetId,
            ':creation_date' => date('Y-m-d H:i:s'),
        ])) {
            $newId = $this->db->lastInsertId();
            $this->processHashtags($newId, $trimmedContent);

            $this->notificationModel->addNotification($viewerId, $parentTweet['id_user'] ?? 0, 'reply', $newId);
            return true;
        }

        return false;
    }

    public function toggleLike($tweetId, $viewerId)
    {
        $this->toggleEngagement('likes', (int) $tweetId, (int) $viewerId, 'like');
    }

    public function toggleRetweet($tweetId, $viewerId)
    {
        $this->toggleEngagement('retweet', (int) $tweetId, (int) $viewerId, 'retweet');
    }

    public function toggleBookmark($tweetId, $viewerId)
    {
        $this->toggleEngagement('bookmark', (int) $tweetId, (int) $viewerId, null);
    }

    public function insertTweet($content, $media1, $media2, $media3, $media4)
    {
        $trimmedContent = trim((string) $content);
        if ($trimmedContent === '' && !$media1 && !$media2 && !$media3 && !$media4) {
            return;
        }

        $userId = $_SESSION['user']['id'];
        $query = $this->db->prepare(
            "INSERT INTO tweet (id_user, content, media1, media2, media3, media4, creation_date) VALUES (:id_user, :content, :media1, :media2, :media3, :media4, :creation_date)"
        );
        if ($query->execute([
            ':id_user' => $userId,
            ':content' => $trimmedContent,
            ':media1' => $media1,
            ':media2' => $media2,
            ':media3' => $media3,
            ':media4' => $media4,
            ':creation_date' => date('Y-m-d H:i:s')
        ])) {
            $newId = $this->db->lastInsertId();
            $this->processHashtags($newId, $trimmedContent);
        }
    }

    private function processHashtags($tweetId, $content)
    {
        preg_match_all('/#(\w+)/', $content, $matches);
        $hashtags = array_unique($matches[1]);

        foreach ($hashtags as $tagName) {
            $stmt = $this->db->prepare("INSERT INTO hashtag (name) VALUES (:name) ON DUPLICATE KEY UPDATE name = name");
            $stmt->execute(['name' => $tagName]);
            
            $stmt = $this->db->prepare("SELECT id FROM hashtag WHERE name = :name");
            $stmt->execute(['name' => $tagName]);
            $tagId = $stmt->fetchColumn();

            $stmt = $this->db->prepare("INSERT IGNORE INTO tweet_hashtag (id_tweet, id_hashtag) VALUES (:tid, :hid)");
            $stmt->execute(['tid' => $tweetId, 'hid' => $tagId]);
        }
    }

    private function toggleEngagement($table, $tweetId, $viewerId, $notifType)
    {
        if ($tweetId <= 0 || $viewerId <= 0) {
            return;
        }

        if (!in_array($table, ['likes', 'retweet', 'bookmark'], true)) {
            return;
        }

        $stmt = $this->db->prepare("SELECT id_user FROM tweet WHERE id = :tid");
        $stmt->execute(['tid' => $tweetId]);
        $tweetAuthorId = $stmt->fetchColumn();
        if (!$tweetAuthorId) return;

        $checkQuery = $this->db->prepare(
            "SELECT COUNT(*) FROM {$table} WHERE id_user = :viewerId AND id_tweet = :tweetId"
        );
        $checkQuery->execute([
            'viewerId' => $viewerId,
            'tweetId' => $tweetId,
        ]);

        $alreadyExists = (int) $checkQuery->fetchColumn() > 0;

        if ($alreadyExists) {
            $deleteQuery = $this->db->prepare(
                "DELETE FROM {$table} WHERE id_user = :viewerId AND id_tweet = :tweetId"
            );
            $deleteQuery->execute([
                'viewerId' => $viewerId,
                'tweetId' => $tweetId,
            ]);
            return;
        }

        $insertQuery = $this->db->prepare(
            "INSERT INTO {$table} (id_user, id_tweet) VALUES (:viewerId, :tweetId)"
        );
        if ($insertQuery->execute([
            'viewerId' => $viewerId,
            'tweetId' => $tweetId,
        ])) {
            if ($notifType) {
                $this->notificationModel->addNotification($viewerId, $tweetAuthorId, $notifType, $tweetId);
            }
        }
    }

    private function tweetExists($tweetId)
    {
        $query = $this->db->prepare("SELECT id FROM tweet WHERE id = :tweetId");
        $query->execute(['tweetId' => $tweetId]);

        return (bool) $query->fetchColumn();
    }
}
