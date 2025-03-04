<?php
session_start();
include('../../models/DBconnection.php');
include('../../models/UserModel.php');

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$usermodel = new UserModel($conn);
$username = $_SESSION['username'];
$user_id = $usermodel->get_user_id($username);

if (!$user_id) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

$userdata = [
    'username' => $usermodel->get_username($user_id),
    'nom' => $usermodel->get_user_nom($user_id),
    'prenom' => $usermodel->get_user_prenom($user_id),
    'clubs' => []
];

$club_ids = $usermodel->get_user_clubs($user_id);
if (!is_array($club_ids)) {
    $club_ids = []; 
}

foreach ($club_ids as $club_id) {
    $club_id = (int) $club_id;
    $result = $conn->query("SELECT club_id, nom FROM club WHERE club_id = $club_id");
    
    if ($result && $club = $result->fetch_assoc()) {
        $userdata['clubs'][] = [
            'id' => $club['club_id'],
            'nom' => $club['nom']
        ];
    }
}

echo json_encode(['user' => $userdata]);
$conn->close();
?>
