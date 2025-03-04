<?php
session_start();
header('Content-Type: application/json');

include('../../models/DBconnection.php'); 
include('../../models/UserModel.php');

$username = $_POST['username'];
$password = $_POST['password'];

$userModel = new UserModel($conn);
$login = $userModel->login($username, $password);

$user_id = $userModel->get_user_id($username);
$nom = $userModel->get_user_nom($user_id);
$prenom = $userModel->get_user_prenom($user_id);
$clubs = $userModel->get_user_clubs($user_id);

$_SESSION['user_id'] = $user_id;
$_SESSION['username'] = $username;
$_SESSION['nom'] = $nom;
$_SESSION['prenom'] = $prenom;
$_SESSION['clubs'] = $clubs;

echo json_encode([
  'success' => true,
  'message' => 'Login successful',
  'user' => [
      'user_id' => $user_id,
      'username' => $username,
      'nom' => $nom,
      'prenom' => $prenom,
      'clubs' => $clubs
  ]
]);

$conn->close();
?>