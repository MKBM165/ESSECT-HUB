<?php
include('DBconnection.php');

class MemberModel {
  private $conn;
 
  public function __construct($conn) {
    $this->conn = $conn;
  }

  // Private function to execute a query and return the result
  private function result_query($query, $params = []) {
    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);
    return $stmt;
  }

  public function join_club($user_id, $club_id) {
    $stmt = $this->conn->prepare("INSERT INTO member (user_id, club_id) VALUES (:user_id, :club_id)");
    return $stmt->execute(['user_id' => $user_id, 'club_id' => $club_id]);
  }

  public function leave_club($user_id, $club_id) {
    $stmt = $this->conn->prepare("DELETE FROM member WHERE user_id = :user_id AND club_id = :club_id");
    return $stmt->execute(['user_id' => $user_id, 'club_id' => $club_id]);
  }

  public function get_user_clubs($user_id) {
    $stmt = $this->conn->prepare("SELECT club_id FROM member WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $clubs = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $clubs[] = $row['club_id'];
    }
    return $clubs;
  }

  public function get_club_members($club_id) {
    $stmt = $this->conn->prepare("SELECT user_id FROM member WHERE club_id = :club_id");
    $stmt->execute(['club_id' => $club_id]);
    $members = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $members[] = $row['user_id'];
    }
    return $members;
  }
}
?>
