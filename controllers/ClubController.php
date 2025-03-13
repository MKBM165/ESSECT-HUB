<?php
session_start();
include('../models/DBconnection.php');
include('../models/ClubModel.php');

header('Content-Type: application/json');

class ClubController {
    private $clubModel;

    public function __construct($conn) {
        $this->clubModel = new ClubModel($conn);
    }

    public function upload_club_image() {
        if (!isset($_FILES['club_image'])) {
            echo json_encode(['error' => 'Club image is required']);
            exit;
        }

        $club_id = $_POST['club_id'] ?? null;
        if ($club_id) {
            $targetDir = "../assets/uploads/";
            $fileName = time() . "_" . basename($_FILES['club_image']['name']);
            $targetFilePath = $targetDir . $fileName;

            // Validate file type (e.g., allow only images)
            $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                echo json_encode(['error' => 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.']);
                exit;
            }

            // Validate file size (e.g., maximum 2MB)
            if ($_FILES['club_image']['size'] > 2 * 1024 * 1024) {
                echo json_encode(['error' => 'File size exceeds 2MB']);
                exit;
            }

            if (move_uploaded_file($_FILES['club_image']['tmp_name'], $targetFilePath)) {
                $imagePath = "assets/uploads/" . $fileName;
                // Call the model method to update the club image
                $this->clubModel->uploadClubImage($club_id, $imagePath);
                echo json_encode(['success' => true, 'image_url' => $imagePath]);
            } else {
                echo json_encode(['error' => 'Upload failed']);
            }
        } else {
            echo json_encode(['error' => 'Club ID is required']);
        }
    }

    public function create_club() {
        if (!isset($_SESSION['username'])) {
            echo json_encode(['error' => 'User not logged in']);
            exit;
        }

        $nom = $_POST['nom'] ?? null;
        $username = $_POST['username'] ?? null;
        $club_desc = $_POST['club_desc'] ?? null;
        $password = $_POST['password'] ?? null;
        $email = $_POST['email'] ?? null;

        if (!isset($_FILES['club_image'])) {
            echo json_encode(['error' => 'Club image is required']);
            exit;
        }

        $image_path = '../uploads/' . basename($_FILES['club_image']['name']);
        move_uploaded_file($_FILES['club_image']['tmp_name'], $image_path);

        if ($nom && $username && $club_desc && $password && $email) {
            if ($this->clubModel->create_club($nom, $username, $club_desc, $image_path, $password, $email)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Club creation failed']);
            }
        } else {
            echo json_encode(['error' => 'Please fill in all fields']);
        }
    }

    public function delete_club() {
        if (!isset($_SESSION['username'])) {
            echo json_encode(['error' => 'User not logged in']);
            exit;
        }

        $club_id = $_POST['club_id'] ?? null;
        if ($club_id) {
            if ($this->clubModel->delete_club($club_id)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Club deletion failed']);
            }
        } else {
            echo json_encode(['error' => 'Club ID is required']);
        }
    }
    public function get_all_clubs() {
        $clubs = $this->clubModel->get_all_clubs();
    
        if ($clubs) {  
            echo json_encode(['success' => true, 'clubs' => $clubs]);
        } else {
            echo json_encode(['error' => 'Clubs not found']);
        }
    }
}

$clubController = new ClubController($conn);
$action = $_POST['action'] ?? null;

switch ($action) {
    case 'getclubs':
        $clubController->get_all_clubs();
        break;
    case 'upload_club_image':
        $clubController->upload_club_image();
        break;
    case 'create_club':
        $clubController->create_club();
        break;
    case 'delete_club':
        $clubController->delete_club();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

$conn = null;
?>
