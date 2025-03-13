<?php
session_start();
include('../models/DBconnection.php');
include('../models/JoinModel.php');
header('Content-Type: application/json');

class RequestController{

private $requestModel;

public function __construct($conn){
 $this->requestModel = new RequestModel($conn);
}

public function get_user_requests(){
  $user_id= $_POST['user_id']??null;

  if($user_id){
    $requests=$this->requestModel->get_user_requests($user_id);

    if ($requests){
      echo json_encode(['success'=> true ,'requests' => $requests]);
    }
    else {
      echo json_encode(['error'=> 'no requests found',]);
    }
  }
  exit;
}

public function get_club_requests(){
  $club_id=$_POST['club_id']??null;
  if($club_id){
    $requests=$this->requestModel->get_club_requests($club_id);
    if($requests){
      echo json_encode(['success' => true ,'requests' => $requests]);
    }
    else {
      echo json_encode(['error'=>'no requests found']);
    }
   }
   exit;
  }
}

$requestController = new RequestController($conn);
$action =$_POST['action']??null;

switch($action){
  case 'get_user_requests':
    $requestController->get_user_requests();
    break;
  case 'get_club_requests':
    $requestController->get_club_requests();
    break;
  default:
    echo json_encode(['error' => 'Invalid action']);
    exit;

}

?>