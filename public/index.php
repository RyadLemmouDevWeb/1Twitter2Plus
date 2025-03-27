<?php
session_start();
require "../app/controllers/Feed_Controller.php";
require "../app/controllers/Account_Controller.php";
require "../app/controllers/Login_Controller.php";

require_once "../app/controllers/Feed_Controller.php";
require_once "../app/controllers/Account_Controller.php";
require_once "../app/controllers/Message_Controller.php";

$request = $_SERVER['REQUEST_URI'] ?? '/login';
$viewDir = "../app/views/";
$controllerDir = "../app/controllers/";

$res = explode('?', $request);

switch ($res[0]) {
    case '/':
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new Login_Controller();

            if (isset($_POST['login'])) {
                $controller->login();
                header("Location: /feed");
                exit();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'register') {
                $controller->register();
                header("Location: /feed");
                exit();
            }
        } else {
            require $viewDir . 'Login_View.php';
        }
        break;

    case '/messages':
        $controller = new MessageController();
        $conversations = $controller->showDiscusion();
        require_once $viewDir . "Message_View.php";
        break;

    case '/message':
        $controller = new MessageController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->createMessage();
        }

        $messageConversation = $controller->showMessage();
        require_once $viewDir . 'Message_View.php';
        break;

    case '/feed':
        $controller = new FeedController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->postTweet();
            header("Location: /feed");
            exit();
        }

        $tweetForFeed = $controller->getTweetForFeed();
        $suggestUsers = $controller->suggestUsers();
        require $viewDir . 'Feed_View.php';
        break;

    case '/account':
        $controller = new AccountController();
        $userId = $_SESSION['user']['id'] ?? null; 

        if ($userId) {
            $controller->showAccountPage($userId);
        } else {
            header("Location: /login");
            exit();
        }
        break;

    case '/korg':
        require_once $viewDir . 'Korg_View.php';
        break;

    default:
        http_response_code(404);
        require_once $viewDir . '404.php';
        break;
    }
