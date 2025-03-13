<?php 
include ('DBconnection.php');

class RequestModel{

  private $conn;

  public function __construct(){
    $this->conn=$conn;
  }

  private function result_query($query,$params=[]){
   $stmt =$this->conn->prepare($query);
   $stmt->execute($params);
   return $stmt;
  }

  public function get_club_requests($club_id){
    $query="SELECT u.user_id ,u.username ,u.cv ,j.status 
            FROM join_requests j JOIN users u 
            ON j.user_id = u.user_id
            WHERE j.club_id=?";
    return $this -> result_query($query,[$club_id])->fetchAll();
  }

 public function get_user_requests($user_id){
  $query="SELECT c.club_id , c.nom  ,j.status
          FROM join_requests j JOIN club c
          ON j.club_id = c.club_id
          WHERE j.user_id=?";
  return $this->result_query($query,[$user_id])->fetchAll();
 }


 public function make_request($user_id,$club_id){
  $query="INSERT INTO join_requests (user_id, club_id) VALUES (?, ?)";
  return $this->result_query($query,[$user_id,$club_id]);
 }

 public function update_request_status($user_id , $club_id ,$newStatus){
  $query="UPDATE join_requests SET status = ?
          WHERE club_id =? 
          AND  user_id=?";
  $this->result_query($query ,[$newStatus,$club_id,$user_id]);
 }
}
?>