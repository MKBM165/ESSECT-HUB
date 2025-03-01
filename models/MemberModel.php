<?php
include('DBconnection.php');

class MemberModel {
  private $conn;
 
  public function __construct($conn) {
   $this->conn = $conn;
  }
 
  public function result_query($query) { 
   return $this->conn->query($query);
  }
 
  public function join_club($user_id, $club_id) {
   $result = $this->result_query("INSERT INTO member (user_id, club_id) VALUES ($user_id, $club_id)");
   return $result;
  }
 
  public function leave_club($user_id, $club_id) {
   $result = $this->result_query("DELETE FROM member WHERE user_id = $user_id AND club_id = $club_id");
   return $result;
  }
 
  public function get_user_clubs($user_id) {
   $result = $this->result_query("SELECT club_id FROM member WHERE user_id = $user_id");
   $clubs = [];
   while ($row = $result->fetch_assoc()) {
    $clubs[] = $row['club_id'];
   }
   return $clubs;
  }
 
  public function get_club_members($club_id) {
   $result = $this->result_query("SELECT user_id FROM member WHERE club_id = $club_id");
   $members = [];
   while ($row = $result->fetch_assoc()) {
    $members[] = $row['user_id'];
   }
   return $members;
  }
 }
 

?>