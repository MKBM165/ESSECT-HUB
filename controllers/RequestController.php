<?php
session_start();
include('../models/DBconnection.php');
include('../models/RequestModel.php');
header('Content-Type: application/json');

class RequestController{

private $requestModel;

public function __construct($conn){
 $this->requestModel = new RequestModel($conn);
}

public function get_user_requests(){
  $user_id= $_POST['user_id']??null;

  if(!$user_id){
   echo json_encode(['error' => 'no user id found']);
   exit;
  }
    $requests=$this->requestModel->get_user_requests($user_id);

    if ($requests){
      echo json_encode(['success'=> true ,'requests' => $requests]);
    }
    else {
      echo json_encode(['error'=> 'no requests found',]);
    }
    exit;
  }



public function get_club_requests(){
  $club_id=$_POST['club_id']??null;

  if(!$club_id){
   echo json_encode(['error' => 'no club id found']);
   exit;
  }

    $requests=$this->requestModel->get_club_requests($club_id);
    if($requests){
      echo json_encode(['success' => true ,'requests' => $requests]);
    }
    else {
      echo json_encode(['error'=>'no requests found']);
    }
   
   exit;
  }

public function add_request(){
  $user_id = $_POST['user_id']??null;
  $club_id = $_POST['club_id']??null;

  if (!$user_id || !$club_id){
    echo json_encode(['error'=> 'Missing parameters']);
    exit;
  }
  
  $result = $this->requestModel->make_request($user_id,$club_id);

  if($result){
    echo json_encode(['success' => true , 'message' => 'request sent successfully']);
  }
  else{
    echo json_encode(['error'=>'Failed to send request']);
  }
 
exit;
}

public function manage_request(){
  $user_id = $_POST['user_id'] ?? null;
  $club_id = $_POST['club_id'] ?? null;
  $new_status = $_POST['new_status'] ??null;

  if(!$user_id || !$club_id || !$new_status){
    echo json_encode(['error'=>'Missing parameters']);
    exit;
  }

  //valid status
  $valid_status = ['accepted','rejected'];

  if(!in_array($new_status,$valid_status)){
    echo json_encode(['error' => 'invalid status']);
    exit;
  }
  //the actual update
  $updated = $this->requestModel->update_request_status($user_id,$club_id,$new_status);
  if ($updated){
    echo json_encode(['success' => true , 'message' => 'request status has been updated']);
  }
  else{
    echo json_encode(['error' => 'failed to update the request status']);
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
  case 'add_request':
    $requestController->add_request();
    break;
  case 'manage_request':
    $requestController->manage_request();
    break;
  default:
    echo json_encode(['error' => 'Invalid action']);
    exit;

}

?>