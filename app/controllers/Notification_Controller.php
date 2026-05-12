<?php
require_once __DIR__ . '/../models/Notification_Model.php';

class NotificationController
{
    private $model;

    public function __construct()
    {
        $this->model = new NotificationModel();
    }

    public function index()
    {
        $viewerId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($viewerId === 0) {
            header("Location: /login");
            exit();
        }

        $notifications = $this->model->getNotificationsForUser($viewerId);
        $this->model->markAsRead($viewerId);

        require __DIR__ . '/../views/Notification_View.php';
    }
}
