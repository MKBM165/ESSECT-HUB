<?php
include('DBconnection.php');

class PostModel {
  private $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  public function result_query($query) {
    return $this->conn->query($query);
  }

  public function createPost($club_id, $caption, $image) {
    $stmt = $this->conn->prepare("INSERT INTO posts (club_id, caption, image, created_at) VALUES (:club_id, :caption, :image, NOW())");
    $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
    $stmt->bindParam(':caption', $caption, PDO::PARAM_STR);
    $stmt->bindParam(':image', $image, PDO::PARAM_STR);
    return $stmt->execute();
  }

  public function deletePost($post_id) {
    $stmt = $this->conn->prepare("DELETE FROM posts WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  public function get_club_posts($club_id) {
    $stmt = $this->conn->prepare("SELECT * FROM posts WHERE club_id = :club_id ORDER BY created_at DESC");
    $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function get_user_feed($user_id) {
    $stmt = $this->conn->prepare("SELECT posts.* FROM posts
                                 JOIN member ON posts.club_id = member.club_id
                                 WHERE member.user_id = :user_id
                                 ORDER BY posts.created_at DESC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function uploadPostImage($postId) {
    $targetDir = "../assets/uploads/";
    $fileName = time() . "_" . basename($_FILES['image']['name']);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
        $imagePath = "assets/uploads/" . $fileName;
        $stmt = $this->conn->prepare("UPDATE posts SET image = :image WHERE post_id = :post_id");
        $stmt->bindParam(':image', $imagePath, PDO::PARAM_STR);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();

        return ["status" => "success", "image_url" => $imagePath];
    } else {
        return ["status" => "error", "message" => "Upload failed"];
    }
  }
}
?>
