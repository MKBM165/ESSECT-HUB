<?php
session_start();
include('../models/DBconnection.php');
include('../models/ReactionModel.php');

header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

class ReactionController {
    private $reactionModel;

    public function __construct($conn) {
        $this->reactionModel = new ReactionModel($conn);
    }

    // React to a post or update reaction
    public function react_to_post() {
        $user_id = $_SESSION['user_id'];
        $post_id = $_POST['post_id'] ?? null;
        $reaction = $_POST['reaction'] ?? null;

        if ($post_id && $reaction) {
            if ($this->reactionModel->reactToPost($user_id, $post_id, $reaction)) {
                echo json_encode(['success' => true, 'message' => 'Reaction recorded']);
            } else {
                echo json_encode(['error' => 'Failed to react']);
            }
        } else {
            echo json_encode(['error' => 'Post ID and reaction are required']);
        }
    }

    // Get reactions for a post
    public function get_post_reactions() {
        $post_id = $_POST['post_id'] ?? null;

        if ($post_id) {
            $reactions = $this->reactionModel->get_post_reactions($post_id);
            if ($reactions) {
                echo json_encode(['success' => true, 'reactions' => $reactions]);
            } else {
                echo json_encode(['error' => 'No reactions found for this post']);
            }
        } else {
            echo json_encode(['error' => 'Post ID is required']);
        }
    }

    // Remove a reaction
    public function remove_reaction() {
        $user_id = $_SESSION['user_id'];
        $post_id = $_POST['post_id'] ?? null;

        if ($post_id) {
            if ($this->reactionModel->removeReaction($user_id, $post_id)) {
                echo json_encode(['success' => true, 'message' => 'Reaction removed']);
            } else {
                echo json_encode(['error' => 'Failed to remove reaction']);
            }
        } else {
            echo json_encode(['error' => 'Post ID is required']);
        }
    }
}

// Communicating with frontend
$reactionController = new ReactionController($conn);
$action = $_POST['action'] ?? null;

switch ($action) {
    case 'react_to_post':
        $reactionController->react_to_post();
        break;
    case 'get_post_reactions':
        $reactionController->get_post_reactions();
        break;
    case 'remove_reaction':
        $reactionController->remove_reaction();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

$conn = null;
?>
