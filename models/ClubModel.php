<?php
include('DBconnection.php');

class ClubModel{
  private $conn;

  public function __construct($conn){
    $this->conn=$conn;
  }

//fonctions to improve the strcucture of the code by minimizing reusibility
private function result_query($query){
  return $this->conn->query($query);
}


  // ************************************************************ --  GETTERS  --***************************************************************************//


  public function get_club_name($club_id){
    $result=$this->result_query("SELECT nom FROM club where club_id=$club_id");

    if ($result && $row = $result->fetch_assoc()) {
      return $row['nom'];
    }
    return null;//no club for this id
  }

  public function get_club_username($club_id){
    $result=$this->result_query("SELECT username FROM club where club_id=$club_id");

    if ($result && $row = $result->fetch_assoc()) {
      return $row['username'];
    }
    return null;//no club for this id
  }

 public function get_club_password($club_id){
  $result=$this->result_query("SELECT password FROM club WHERE club_id=$club_id");

  if ($result && $row = $result->fetch_assoc()) {
    return $row['password'];
  }
  return null;//no club for this id 
 }

 public function get_club_id($username){
  $result=$this->result_query("SELECT club_id FROM club WHERE username='$username'");

  if ($result && $row = $result->fetch_assoc()) {
    return $row['club_id'];
  }
  return null;//no club of the input username
 }

 public function get_club_desc($club_id) {
  $result=$this->result_query("SELECT club_desc FROM club WHERE club_id = $club_id");

  if ($result && $row = $result->fetch_assoc()) {
    return $row['club_desc']; 
  }
  return null;
}

public function get_club_img($club_id){
  $result=$this->result_query("SELECT club_image FROM club WHERE club_id = $club_id");

  if ($result && $row = $result->fetch_assoc()) {
    return $row['club_image']; //a url of the image witch is stored in the database 
  }
  return null;
}

public function get_club_email($club_id){
  $result=$this->result_query("SELECT email FROM club WHERE club_id = $club_id");

  if ($result && $row = $result->fetch_assoc()) {
    return $row['email']; 
  }
  return null;
}

public function get_club_date($club_id){
  $result=$this->result_query( "SELECT date_creation FROM club  WHERE club_id=$club_id");

 if($result && $row=$result->fetch_assoc()){
  $date = new DateTime($row['date_creation']);
  return $date->format('d/m/y');
 }
 return null;
}

public function get_all_clubs(){
  $result=$this->result_query("SELECT * FROM club");
  return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

  // ************************************************************ --  AUTHENTIFICATION  --***************************************************************************//
  public function login($username,$password){

    $club_id=$this->get_club_id($username);

    if($club_id !== null){
     $db_password=$this->get_club_password($club_id);
    
     if($db_password !== null && password_verify($password,$db_password)){
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
  $hashedPassword=password_hash($new_password,PASSWORD_DEFAULT);
  $query="UPDATE club SET password ='$hashedPassword' where club_id = $club_id ";
  return $this->conn->query($query);
 }
 
 public function create_club($nom, $username, $club_desc, $club_image, $password, $email){
  return $this->result_query("INSERT INTO club (nom, username, club_desc, club_image, password, email, date_creation) 
                              VALUES ('$nom', '$username', '$club_desc', '$club_image', '$password', '$email', NOW())")
         ? true : false;
}


public function delete_club($club_id){
  $result = $this->result_query("SELECT club_id FROM club WHERE club_id = $club_id");
  if ($result->num_rows > 0) {
      return $this->result_query("DELETE FROM club WHERE club_id = $club_id");
  }
  return false;
}


}
?>