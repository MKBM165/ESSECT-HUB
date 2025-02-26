<?php
include('DBconnection.php');

class ClubModel{
  private $conn;

  public function __construct($conn){
    $this->conn=$conn;
  }

  // ************************************************************ --  GETTERS  --***************************************************************************//

  public function get_club_name($club_id){
    $query="SELECT nom FROM club where club_id=$club_id";
    $result=$this->conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
      return $row['nom'];
    }
    return null;//no club for this id
  }

  public function get_club_username($club_id){
    $query="SELECT username FROM club where club_id=$club_id";
    $result=$this->conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
      return $row['username'];
    }
    return null;//no club for this id
  }

 public function get_club_password($club_id){
  $query="SELECT password FROM club WHERE club_id=$club_id";
  $result=$this->conn->query($query);
  if ($result && $row = $result->fetch_assoc()) {
    return $row['password'];
  }
  return null;//no club for this id 
 }

 public function get_club_id($username){
  $query="SELECT club_id FROM club WHERE username='$username'";
  $result=$this->conn->query($query);

  if ($result && $row = $result->fetch_assoc()) {
    return $row['club_id'];
  }
  return null;//no club of the input username
 }

 public function get_club_desc($club_id) {
  $query = "SELECT club_desc FROM club WHERE club_id = $club_id";
  $result = $this->conn->query($query);

  if ($result && $row = $result->fetch_assoc()) {
    return $row['club_desc']; 
  }
  return null;
}

public function get_club_img($club_id){
  $query = "SELECT club_image FROM club WHERE club_id = $club_id";
  $result = $this->conn->query($query);

  if ($result && $row = $result->fetch_assoc()) {
    return $row['club_image']; //a url of the image witch is stored in the database 
  }
  return null;
}

public function get_club_email($club_id){
  $query = "SELECT email FROM club WHERE club_id = $club_id";
  $result = $this->conn->query($query);
  if ($result && $row = $result->fetch_assoc()) {
    return $row['email']; 
  }
  return null;
}

public function get_club_date($club_id){
 $query = "SELECT date_creation FROM club  WHERE club_id=$club_id";
 $result=$this->conn->query($query);

 if($result && $row=$result->fetch_assoc()){
  $date = new DateTime($row['date_creation']);
  return $date->format('d/m/y');
 }
 return null;
}

  // ************************************************************ --  AUTHENTIFICATION  --***************************************************************************//
  public function club_login($username,$password){

    $club_id=$this->get_club_id($username);

    if($club_id !== null){
     $db_password=$this->get_club_password($club_id);
    
     if($db_password !== null && $db_password===$password){
      return true;
     }
    }
    return false;
  }


  // ************************************************************ --  ENABLE MODIFICATION  --***************************************************************************//
  
 public function change_username($club_id,$new_username){
  $query="UPDATE club SET username ='$new_username' where club_id =$club_id";
  return $this->conn->query($query);
 }

 public function change_password($club_id,$new_password){
  $query="UPDATE club SET password ='$new_password' where club_id = $club_id ";
  return $this->conn->query($query);
 }
 

}
?>