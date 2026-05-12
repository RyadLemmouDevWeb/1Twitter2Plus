<?php
require_once __DIR__ . '/../models/Bookmark_Model.php';

class BookmarkController
{
    private $model;

    public function __construct()
    {
        $this->model = new BookmarkModel();
    }

    public function index()
    {
        $viewerId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($viewerId === 0) {
            header("Location: /login");
            exit();
        }

        $bookmarks = $this->model->getBookmarksForUser($viewerId);
        require __DIR__ . '/../views/Bookmark_View.php';
    }
}
