<?php
session_start();
include('../models/DBconnection.php');
include('../models/AdminModel.php');

header('Content-Type: application/json');

class AdminController {
    private $adminModel;

    public function __construct($conn) {
        $this->adminModel = new AdminModel($conn);
    }

    public function login() {/*
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$username || !$password) {
            echo json_encode(['error' => 'Username and password are required']);
            return;
        }

        if ($this->adminModel->login($username, $password)) {
            $_SESSION['username'] = $username;
            echo json_encode(['success' => true, 'message' => 'Login successful']);
            } else {
                echo json_encode(['error' => 'Invalid credentials']);
        }*/
        $_SESSION['username'] = 'admin';
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    }
    public function getpass(){
        
        echo json_encode(['success' => true, 'message' => $this->adminModel->get_admin_password()]);
    }
}

$adminController = new AdminController($conn);
$action = $_POST['action'] ?? null;

switch ($action) {
    case 'login':
        $adminController->login();
        break;
    case 'getpass':
            $adminController->getpass();
        break;
    case 'logout':
        session_destroy();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
?>
