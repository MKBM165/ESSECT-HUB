<?php
session_start();
include('../../models/DBconnection.php');
include('../../models/UserModel.php');
include('../../models/ClubModel.php'); 

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$usermodel = new UserModel($conn);
$clubmodel = new ClubModel($conn); 

$username = $_SESSION['username'];
$user_id = $usermodel->get_user_id($username);

if (!$user_id) {
    echo json_encode(['error' => 'User not found']);
    exit;
}


$user_data = $usermodel->get_user_by_id($user_id);
if (!$user_data) {
    echo json_encode(['error' => 'User data not found']);
    exit;
}

$userdata = [
    'username' => $user_data['username'],
    'nom' => $user_data['nom'],
    'prenom' => $user_data['prenom'],
    'clubs' => []
];

// Getting the clubs the user is a member of
$club_ids = $usermodel->get_user_clubs($user_id);
if (!is_array($club_ids)) {
    $club_ids = []; 
}

foreach ($club_ids as $club_id) {
    $club_id = (int) $club_id;
    
    $club_name = $clubmodel->get_club_name($club_id);
    $club_desc = $clubmodel->get_club_desc($club_id);
    $club_img = $clubmodel->get_club_img($club_id);
    $club_email = $clubmodel->get_club_email($club_id);

    if ($club_name) {
        // Adding club data to the user data array
        $userdata['clubs'][] = [
            'id' => $club_id,
            'nom' => $club_name,
            'desc' => $club_desc,
            'image' => $club_img,
            'email' => $club_email
        ];
    }
}

echo json_encode(['user' => $userdata]);
$conn->close();
?>
