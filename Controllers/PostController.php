<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/PostModel.php';

class PostController {
    private $postModel;

    public function __construct() {
        $database = new Database();
        $db = $database->connect();
        $this->postModel = new PostModel($db);
    }

    public function index() {
        return $this->postModel->getAllPosts();
    }

    public function create() {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION['UserID'] ?? 1;
        $content = trim($_POST['content'] ?? '');

        if ($content === '' && empty($_FILES['images']['name'][0])) {
            echo json_encode([
                "success" => false,
                "message" => "Vui lòng nhập nội dung hoặc chọn ảnh."
            ]);
            return;
        }

        $postId = $this->postModel->createPost($userId, $content);

        if (!$postId) {
            echo json_encode([
                "success" => false,
                "message" => "Không thể tạo bài viết."
            ]);
            return;
        }

        $uploadedImages = [];

        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../Public/assets/img/posts/';;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['images']['name'] as $key => $fileName) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['images']['tmp_name'][$key];

                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                    if (!in_array($ext, $allowed)) {
                        continue;
                    }

                    $newName = uniqid('post_', true) . '.' . $ext;
                    $serverPath = $uploadDir . $newName;
                    $dbPath = 'Public/assets/img/posts/' . $newName;

                    if (move_uploaded_file($tmpName, $serverPath)) {
                        $this->postModel->addPostImage($postId, $dbPath);
                        $uploadedImages[] = $dbPath;
                    }
                }
            }
        }

        echo json_encode([
            "success" => true,
            "message" => "Đăng bài thành công.",
            "post" => [
                "PostID" => $postId,
                "Content" => $content,
                "Images" => $uploadedImages,
                "FullName" => $_SESSION['FullName'] ?? 'Bạn',
                "Username" => $_SESSION['Username'] ?? 'demo_user',
                "ProfilePictureUrl" => $_SESSION['ProfilePictureUrl'] ?? '',
                "LikeCount" => 0,
                "CommentCount" => 0,
                "CreatedAt" => "vừa xong"
            ]
        ]);
    }

    public function like() {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION['UserID'] ?? 1;
        $postId = $_POST['postId'] ?? null;

        if (!$postId) {
            echo json_encode([
                "success" => false,
                "message" => "Thiếu PostID."
            ]);
            return;
        }

        $status = $this->postModel->toggleLike($userId, $postId);
        $likeCount = $this->postModel->countLikes($postId);

        echo json_encode([
            "success" => true,
            "status" => $status,
            "likeCount" => $likeCount
        ]);
    }

    public function comment() {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION['UserID'] ?? 1;
        $postId = $_POST['postId'] ?? null;
        $content = trim($_POST['content'] ?? '');

        if (!$postId || $content === '') {
            echo json_encode([
                "success" => false,
                "message" => "Thiếu nội dung bình luận."
            ]);
            return;
        }

        $result = $this->postModel->createComment($userId, $postId, $content);

        echo json_encode([
            "success" => $result,
            "comment" => [
                "content" => $content,
                "fullName" => $_SESSION['FullName'] ?? 'Bạn'
            ]
        ]);
    }
    public function getComments($postId) {
    return $this->postModel->getCommentsByPostId($postId);
}
}

if (isset($_GET['action'])) {
    $controller = new PostController();

    if ($_GET['action'] === 'create') {
        $controller->create();
    }

    if ($_GET['action'] === 'like') {
        $controller->like();
    }

    if ($_GET['action'] === 'comment') {
        $controller->comment();
    }

}
?>