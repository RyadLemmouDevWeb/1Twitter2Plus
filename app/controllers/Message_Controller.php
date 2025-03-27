<?php
require "../app/models/Message_Model.php";

class MessageController
{
  private $messageModel;
  public function __construct()
  {
    $this->messageModel = new Message_Model();
  }

  public function showMessage() {
      $userId = $this->messageModel->idFromUsername($_GET['username']);
      return $this->messageModel->showMessage(3, $userId["id"]);
    //   return $this->messageModel->showMessage(3, $userId);
    //   return $this->messageModel->showMessage($_SESSION['user']['id'], $userId);
    // return $this->messageModel->showMessages($id_sender, $id_receiver);
    # code...
    
}


public function createMessage()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_sender = 3;
        // $id_sender = $_SESSION['user']['id'];
        $id_receiver = $this->messageModel->idFromUsername($_GET['username']);
        $content = $_POST['content'];
        $media = $_POST['media'] ?? null;
        // var_dump($id_sender, $id_receiver["id"], $content, $media);
        $this->messageModel->insertMessage($id_sender, $id_receiver["id"], $content, $media);
        header("Location: /message?username=". $_GET['username']);
    }
}

public function showDiscusion(){
    // $id_user = $_SESSION['user'];
    $id_user = 3;
    return $this->messageModel->showDiscusion($id_user);
  }



}

