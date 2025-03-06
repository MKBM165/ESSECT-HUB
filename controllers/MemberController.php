<?php
session_start();
include('../models/DBconnection.php');
include('../models/MemberModel.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

class MemberController {
    private $memberModel;

    public function __construct($conn) {
        $this->memberModel = new MemberModel($conn);
    }

    public function join_club() {
        $user_id = $_SESSION['user_id'];
        $club_id = $_POST['club_id'] ?? null;

        if ($club_id) {
            try {
                if ($this->memberModel->join_club($user_id, $club_id)) {
                    echo json_encode(['success' => true, 'message' => 'Joined the club']);
                } else {
                    echo json_encode(['error' => 'Failed to join the club']);
                }
            } catch (Exception $e) {
                echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'Club ID is required']);
        }
    }

    public function leave_club() {
        $user_id = $_SESSION['user_id'];
        $club_id = $_POST['club_id'] ?? null;

        if ($club_id) {
            try {
                if ($this->memberModel->leave_club($user_id, $club_id)) {
                    echo json_encode(['success' => true, 'message' => 'Left the club']);
                } else {
                    echo json_encode(['error' => 'Failed to leave the club']);
                }
            } catch (Exception $e) {
                echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'Club ID is required']);
        }
    }

    public function get_user_clubs() {
        $user_id = $_SESSION['user_id'];
        try {
            $clubs = $this->memberModel->get_user_clubs($user_id);
            echo json_encode(['success' => true, 'clubs' => $clubs]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function get_club_members() {
        $club_id = $_POST['club_id'] ?? null;

        if ($club_id) {
            try {
                $members = $this->memberModel->get_club_members($club_id);
                echo json_encode(['success' => true, 'members' => $members]);
            } catch (Exception $e) {
                echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'Club ID is required']);
        }
    }
}

$memberController = new MemberController($conn);
$action = $_POST['action'] ?? null;

switch ($action) {
    case 'join_club':
        $memberController->join_club();
        break;
    case 'leave_club':
        $memberController->leave_club();
        break;
    case 'get_user_clubs':
        $memberController->get_user_clubs();
        break;
    case 'get_club_members':
        $memberController->get_club_members();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

$conn = null;
?>
