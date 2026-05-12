<?php
session_start();
require_once __DIR__ . '/../app/helpers/Security.php';
require_once __DIR__ . '/../app/helpers/Formatting.php';
require_once __DIR__ . '/../app/helpers/Upload.php';
require_once "../app/controllers/Feed_Controller.php";
require_once "../app/controllers/Account_Controller.php";
require_once "../app/controllers/Login_Controller.php";
require_once "../app/controllers/Message_Controller.php";
require_once "../app/controllers/Follow_Controller.php";
require_once "../app/controllers/Engagement_Controller.php";
require_once "../app/controllers/Tweet_Controller.php";
require_once "../app/controllers/Block_Controller.php";
require_once "../app/controllers/Search_Controller.php";
require_once "../app/controllers/Notification_Controller.php";
require_once "../app/controllers/Bookmark_Controller.php";

$request = parse_url($_SERVER['REQUEST_URI'] ?? '/login', PHP_URL_PATH);
$viewDir = "../app/views/";

$requireAuth = static function () {
    if (empty($_SESSION['user']['id'])) {
        header("Location: /login");
        exit();
    }
};

switch ($request) {
    case '/':
    case '/login':
        if (isset($_SESSION['user']['id']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /feed");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new Login_Controller();

            if (isset($_POST['login'])) {
                $controller->login();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'register') {
                $controller->register();
            }
        }

        require $viewDir . 'Login_View.php';
        break;

    case '/logout':
        $controller = new AccountController();
        $controller->logout();
        break;

    case '/messages':
        $requireAuth();
        $controller = new MessageController();
        $conversations = $controller->showDiscusion();
        $messageConversation = [];
        require_once $viewDir . "Message_View.php";
        break;

    case '/message':
        $requireAuth();

        if (empty($_GET['username'])) {
            header("Location: /messages");
            exit();
        }

        $controller = new MessageController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->createMessage();
            exit();
        }

        $conversations = $controller->showDiscusion();
        $messageConversation = $controller->showMessage();
        require_once $viewDir . 'Message_View.php';
        break;

    case '/message/share':
        $requireAuth();
        $controller = new MessageController();
        $controller->shareTweet();
        break;

    case '/feed':
        $requireAuth();
        $controller = new FeedController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->postTweet();
            header("Location: /feed");
            exit();
        }

        $tweetForFeed = $controller->getTweetForFeed();
        $suggestUsers = $controller->suggestUsers();
        $activeTab = $controller->getCurrentTab();
        require $viewDir . 'Feed_View.php';
        break;

    case '/search':
        $requireAuth();
        $controller = new SearchController();
        $controller->index();
        break;

    case '/api/search/users':
        $requireAuth();
        $controller = new SearchController();
        $controller->apiSearchUsers();
        break;

    case '/notifications':
        $requireAuth();
        $controller = new NotificationController();
        $controller->index();
        break;

    case '/bookmarks':
        $requireAuth();
        $controller = new BookmarkController();
        $controller->index();
        break;

    case '/account':
        $requireAuth();
        $controller = new AccountController();
        $viewerId = (int) $_SESSION['user']['id'];

        if (!empty($_GET['username'])) {
            $controller->showAccountPageByUsername($_GET['username'], $viewerId);
        } else {
            $controller->showAccountPage($viewerId, $viewerId);
        }
        break;

    case '/account/update':
        $requireAuth();
        $controller = new AccountController();
        $controller->updateProfile();
        break;

    case '/follow':
        $requireAuth();
        $controller = new FollowController();
        $controller->follow();
        break;

    case '/unfollow':
        $requireAuth();
        $controller = new FollowController();
        $controller->unfollow();
        break;

    case '/block':
        $requireAuth();
        $controller = new BlockController();
        $controller->block();
        break;

    case '/unblock':
        $requireAuth();
        $controller = new BlockController();
        $controller->unblock();
        break;

    case '/tweet/like':
        $requireAuth();
        $controller = new EngagementController();
        $controller->toggleLike();
        break;

    case '/tweet/retweet':
        $requireAuth();
        $controller = new EngagementController();
        $controller->toggleRetweet();
        break;

    case '/tweet/bookmark':
        $requireAuth();
        $controller = new EngagementController();
        $controller->toggleBookmark();
        break;

    case '/tweet/reply':
        $requireAuth();
        $controller = new TweetController();
        $tweetId = (int) ($_GET['id'] ?? 0);
        $controller->replyToTweet($tweetId);
        break;

    case '/tweet':
        $requireAuth();
        $controller = new TweetController();
        $tweetId = (int) ($_GET['id'] ?? 0);
        $controller->showTweetPage($tweetId);
        break;

    case '/korg':
        require_once $viewDir . 'Korg_View.php';
        break;

    default:
        http_response_code(404);
        require_once $viewDir . '404.php';
        break;
}
