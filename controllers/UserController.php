<?php
session_start();
include('../models/DBconnection.php');
include('../models/UserModel.php');
header('Content-Type: application/json');



header('Content-Type: application/json');

class UserController {
    private $userModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    public function login() {
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if ($username && $password) {
            if ($this->userModel->login($username, $password)) {
                $_SESSION['username'] = $username;
                echo json_encode(['success' => true, 'message' => 'Login successful']);
            } else {
                echo json_encode(['error' => 'Invalid username or password']);
            }
        } else {
            echo json_encode(['error' => 'Username and password are required']);
        }
        exit;
    }

    public function create_user() {
        $username = $_POST['username'] ?? null;
        $nom = $_POST['nom'] ?? null;
        $prenom = $_POST['prenom'] ?? null;
        $password = $_POST['password'] ?? null;
    
        if (!isset($_FILES['cv'])) {
            echo json_encode(['error' => 'CV file is required']);
            exit;
        }
    
        $cv_path = '../assets/uploads/' . basename($_FILES['cv']['name']);
        
        if (!move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path)) {
            echo json_encode(['error' => 'Failed to upload CV']);
            exit;
        }
    
        if ($username && $nom && $prenom && $password) {
            if ($this->userModel->create_user($username, $nom, $prenom, $cv_path, $password)) {
                echo json_encode(['success' => true, 'message' => 'User created successfully']);
            } else {
                echo json_encode(['error' => 'Failed to create user']);
            }
        } else {
            echo json_encode(['error' => 'All fields are required']);
        }
        exit;
    }
    

    public function change_username() {
        if (!isset($_SESSION['username'])) {
            echo json_encode(['error' => 'User not logged in']);
            exit;
        }

        $user_id = $_POST['user_id'] ?? null;
        $new_username = $_POST['new_username'] ?? null;

        if ($user_id && $new_username) {
            if ($this->userModel->change_username($user_id, $new_username)) {
                echo json_encode(['success' => true, 'message' => 'Username changed successfully']);
            } else {
                echo json_encode(['error' => 'Failed to change username']);
            }
        } else {
            echo json_encode(['error' => 'User ID and new username are required']);
        }
        exit;
    }

    public function get_user_profile() {
        $user_id = $_SESSION['user_id'];
        $user = $this->userModel->get_user_by_id($user_id);
        $clubs = $this->userModel->get_user_clubs($user_id);
    
        if ($user) {
            $user['clubs'] = $clubs;  
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    }

    public function get_user_feed() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'User not logged in']);
            exit;
        }
    
        $user_id = $_SESSION['user_id'];
        $feed = $this->userModel->get_user_feed($user_id);
    
        if ($feed) {
            echo json_encode(['success' => true, 'feed' => $feed]);
        } else {
            echo json_encode(['error' => 'No posts available']);
        }
    }
}

$userController = new UserController($conn);
$action = $_POST['action'] ?? null;

switch ($action) {
    case 'login':
        $userController->login();
        break;
    case 'create_user':
        $userController->create_user();
        break;
    case 'change_username':
        $userController->change_username();
        break;
    case 'get_user_profile':
        $userController->get_user_profile();
        break;
    case 'get_user_feed':
        $userController->get_user_feed();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

$conn = null;
?>