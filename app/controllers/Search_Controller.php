<?php
require_once __DIR__ . '/../models/Search_Model.php';

class SearchController
{
    private $searchModel;

    public function __construct()
    {
        $this->searchModel = new SearchModel();
    }

    public function index()
    {
        $viewerId = (int) ($_SESSION['user']['id'] ?? 0);
        $query = $_GET['q'] ?? '';
        
        $users = [];
        $tweets = [];

        if ($query !== '') {
            $users = $this->searchModel->searchUsers($query, $viewerId);
            $tweets = $this->searchModel->searchTweets($query, $viewerId);
        }

        require __DIR__ . '/../views/Search_View.php';
    }

    public function apiSearchUsers()
    {
        header('Content-Type: application/json');
        $viewerId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($viewerId === 0) {
            echo json_encode([]);
            exit;
        }
        $query = $_GET['q'] ?? '';
        $users = [];
        if ($query !== '') {
            $users = $this->searchModel->searchUsers($query, $viewerId);
        }
        echo json_encode($users);
        exit;
    }
}
