<?php
include('DBconnection.php');

class UserModel{
  private $conn;

  public function __construct($conn){
    $this->conn = $conn;
  }

  // Reusable function for queries
  private function result_query($query){
    return $this->conn->query($query);
  }
  
// ************************************************************ --  GETTERS  --***************************************************************************//
public function get_user_id($username){
  $result = $this->result_query("SELECT user_id FROM users WHERE username='$username'");

  if($result && $row = $result->fetch_assoc()){
    return $row['user_id'];
  }
  return null;
}

public function get_user_by_id($user_id){
  $result = $this->result_query("SELECT * FROM users WHERE user_id = $user_id");
  return $result ? $result->fetch_assoc() : null;
}


public function get_username($user_id){
  $result = $this->result_query("SELECT username FROM users WHERE user_id=$user_id");

  if($result && $row = $result->fetch_assoc()){
    return $row['username'];
  }
  return null;
}

public function get_user_password($user_id){
  $result = $this->result_query("SELECT password FROM users WHERE user_id=$user_id");

  if($result && $row = $result->fetch_assoc()){
    return $row['password'];
  }
  return null;
}

public function get_user_cv($user_id){
  $result = $this->result_query("SELECT cv FROM users WHERE user_id=$user_id");

  if($result && $row = $result->fetch_assoc()){
    return $row['cv'];
  }
  return null;
}

public function get_user_nom($user_id){
  $result = $this->result_query("SELECT nom FROM users WHERE user_id=$user_id");

  if($result && $row = $result->fetch_assoc()){
    return $row['nom'];
  }
  return null;
}

public function get_user_prenom($user_id) {
  $result = $this->result_query("SELECT prenom FROM users WHERE user_id=$user_id");

  if($result && $row = $result->fetch_assoc()){
    return $row['prenom'];
  }
  return null;
}

public function get_user_clubs($user_id){
  $result = $this->result_query("SELECT club_id FROM member WHERE user_id=$user_id"); // Get all club_id's where the user is a member of
  
  $clubs = [];
  if($result){
    while($row = $result->fetch_assoc()){
      $clubs[] = $row['club_id'];
    }
  }

  return $clubs;
}


// ************************************************************ --  AUTHENTIFICATION  --***************************************************************************//
public function login($username, $password){
  $user_id = $this->get_user_id($username);

  if($user_id !== null){
    $db_password = $this->get_user_password($user_id);

    if($db_password !== null && password_verify($password, $db_password)){
      return true;
    }
  }
  return false;
}


// ************************************************************ -- ENABLE MODIFICATION --******************************************************************************//
public function change_username($user_id, $new_username){
  $query = "UPDATE users SET username = '$new_username' WHERE user_id = $user_id";
  return $this->conn->query($query);
}

public function change_password($user_id, $new_password){
  $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
  $query = "UPDATE users SET password = '$hashedPassword' WHERE user_id = $user_id";
  return $this->conn->query($query);
}

public function change_nom($user_id, $new_nom){
  $query = "UPDATE users SET nom = '$new_nom' WHERE user_id = $user_id";
  return $this->conn->query($query);
}

public function change_prenom($user_id, $new_prenom){
  $query = "UPDATE users SET prenom = '$new_prenom' WHERE user_id = $user_id";
  return $this->conn->query($query);
}

public function change_cv($user_id, $new_cv){
  $query = "UPDATE users SET cv = '$new_cv' WHERE user_id = $user_id";
  return $this->conn->query($query);
}

public function change_pic($user_id, $image){
  return $this->result_query("UPDATE users SET profile_pic = '$image' WHERE user_id = $user_id");
}


public function create_user($username, $nom, $prenom, $cv, $password){
   return $this->result_query( "INSERT INTO users (username, nom, prenom, cv, password) 
  VALUES ('$username', '$nom', '$prenom', '$cv', '$password')");
}
}
?>
