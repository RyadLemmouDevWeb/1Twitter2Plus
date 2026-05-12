<?php
require_once __DIR__ . '/../models/Message_Model.php';

class MessageController
{
  private $messageModel;
  public function __construct()
  {
    $this->messageModel = new Message_Model();
  }

  public function showMessage() {
      if (empty($_GET['username']) || empty($_SESSION['user']['id'])) {
        return [];
      }

      $receiver = $this->messageModel->idFromUsername($_GET['username']);
      if (!$receiver || empty($receiver['id'])) {
        return [];
      }

      return $this->messageModel->showMessage((int) $_SESSION['user']['id'], (int) $receiver['id']);
    }


public function createMessage()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
      header("Location: /messages");
      exit();
    }

    if (empty($_SESSION['user']['id']) || empty($_GET['username'])) {
      header("Location: /messages");
      exit();
    }

    $id_sender = (int) $_SESSION['user']['id'];
    $id_receiver = $this->messageModel->idFromUsername($_GET['username']);
    if (!$id_receiver || empty($id_receiver['id'])) {
      header("Location: /messages");
      exit();
    }

    $content = $_POST['content'] ?? '';
        $media = $_POST['media'] ?? null;
    if ($content === '') {
      header("Location: /message?username=" . urlencode($_GET['username']));
      exit();
    }

        $this->messageModel->insertMessage($id_sender, $id_receiver["id"], $content, $media);
    header("Location: /message?username=" . urlencode($_GET['username']));
    exit();
    }
}

public function showDiscusion(){
  if (empty($_SESSION['user']['id'])) {
    return [];
  }

  $id_user = (int) $_SESSION['user']['id'];
    return $this->messageModel->showDiscusion($id_user);
  }

public function shareTweet()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrf_verify()) {
            header("Location: /feed");
            exit();
        }

        $viewerId = (int) ($_SESSION['user']['id'] ?? 0);
        $receiverUsername = $_POST['receiver_username'] ?? '';
        $tweetId = (int) ($_POST['tweet_id'] ?? 0);

        if ($viewerId === 0 || $receiverUsername === '' || $tweetId === 0) {
            header("Location: /feed");
            exit();
        }

        $receiver = $this->messageModel->idFromUsername($receiverUsername);
        if (!$receiver) {
            header("Location: /feed");
            exit();
        }

        $content = "Regarde ce tweet : http://" . $_SERVER['HTTP_HOST'] . "/tweet?id=" . $tweetId;
        $this->messageModel->insertMessage($viewerId, $receiver['id'], $content, null);

        header("Location: /message?username=" . urlencode($receiverUsername));
        exit();
    }
}

}

