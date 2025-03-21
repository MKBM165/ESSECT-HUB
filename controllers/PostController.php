<?php
session_start();
include('../models/DBconnection.php');
include('../models/PostModel.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

class PostController {
    private $postModel;

    public function __construct($conn) {
        $this->postModel = new PostModel($conn);
    }

    public function create_post() {
        $club_id = $_SESSION['club_id'] ?? null;
        // $club_id = $_POST['club_id'] ?? null;
        $caption = $_POST['caption'] ?? null;
        $image = $_POST['image'] ?? null;

        if ($club_id && $caption && $image) {
            if ($this->postModel->createPost($club_id, $caption, $image)) {
                echo json_encode(['success' => true, 'message' => 'Post created']);
            } else {
                echo json_encode(['error' => 'Failed to create post']);
            }
        } else {
            echo json_encode(['error' => 'All fields (club_id, caption, image) are required']);
        }
    }

    public function delete_post() {
        $post_id = $_POST['post_id'] ?? null;

        if ($post_id) {
            if ($this->postModel->deletePost($post_id)) {
                echo json_encode(['success' => true, 'message' => 'Post deleted']);
            } else {
                echo json_encode(['error' => 'Failed to delete post']);
            }
        } else {
            echo json_encode(['error' => 'Post ID is required']);
        }
    }

    public function get_club_posts() {
        $club_id = $_SESSION['club_id'] ?? null;

        if ($club_id) {
            $posts = $this->postModel->get_club_posts($club_id);
            echo json_encode(['success' => true, 'posts' => $posts]);
        } else {
            echo json_encode(['error' => 'Club ID is required']);
        }
    }

    public function get_user_feed() {
        $user_id = $_SESSION['user_id'];

        $posts = $this->postModel->get_user_feed($user_id);
        echo json_encode(['success' => true, 'posts' => $posts]);
    }

    public function upload_post_image() {
        if (!isset($_FILES['image'])) {
            echo json_encode(['error' => 'Image is required']);
            exit;
        }
        $post_id = $_POST['post_id'] ?? null;
        if ($post_id) {
            $targetDir = "../assets/uploads/";
            $fileName = time() . "_" . basename($_FILES['image']['name']);
            $targetFilePath = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = "assets/uploads/" . $fileName;
                $this->postModel->uploadPostImage($post_id);
                echo json_encode(['success' => true, 'image_url' => $imagePath]);
            } else {
                echo json_encode(['error' => 'Upload failed']);
            }
        } else {
            echo json_encode(['error' => 'Post ID is required']);
        }
    }
}

$postController = new PostController($conn);
$_POST = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$action = $_POST['action'] ?? null;

switch ($action) {
    case 'create_post':
        $postController->create_post();
        break;
    case 'delete_post':
        $postController->delete_post();
        break;
    case 'get_club_posts':
        $postController->get_club_posts();
        break;
    case 'get_user_feed':
        $postController->get_user_feed();
        break;
    case 'upload_post_image':
        $postController->upload_post_image();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

$conn->close();
?>
