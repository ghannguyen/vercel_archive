<?php
class Post {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllPosts() {
        $sql = "
            SELECT 
                p.PostID,
                p.Content,
                p.CreatedAt,
                u.UserID,
                u.Username,
                u.FullName,
                u.ProfilePictureUrl,
                GROUP_CONCAT(DISTINCT pi.ImageUrl) AS Images,
                COUNT(DISTINCT l.UserID) AS LikeCount,
                COUNT(DISTINCT c.CommentID) AS CommentCount
            FROM posts p
            JOIN users u ON p.UserID = u.UserID
            LEFT JOIN postimages pi ON p.PostID = pi.PostID
            LEFT JOIN likes l ON p.PostID = l.PostID
            LEFT JOIN comments c ON p.PostID = c.PostID
            GROUP BY p.PostID
            ORDER BY p.CreatedAt DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>