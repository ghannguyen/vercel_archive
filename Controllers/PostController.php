<?php
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/Post.php';

class PostController {
    private $postModel;

    public function __construct() {
        $database = new Database();
        $db = $database->connect();

        $this->postModel = new Post($db);
    }

    public function index() {
        return $this->postModel->getAllPosts();
    }
}
?>