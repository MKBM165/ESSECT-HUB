<?php
include('DBconnection.php');

class AdminModel{
  private $conn;

  public function __construct($conn){
    $this->conn=$conn;
  }

 // functions for resuablility 
  private function result_query($query){
    return $this->conn->query($query);
  }


//get the admin username from admin table 
public function get_db_username(){
  $result=$this->result_query("SELECT username FROM admin LIMIT 1");  //store the query result in $result variable
 
  if ($result->num_rows===1){  //if the result has only one row that means the table is valid 
    $row =$result->fetch_assoc();
    return $row['username'];// string
  }
}


//get the admin password from admin table 
 public function get_db_password(){
  $result=$this->result_query("SELECT password FROM admin LIMIT 1");

   if($result -> num_rows===1){
    $row=$result->fetch_assoc();//store thhe result as an assosiative array in $row variable
    return $row['password'];
   }
   return null;
 }
 

//login function (can be used only for admin)
public function admin_login($username,$password){
  $db_username=$this->get_db_username();
  $db_password=$this->get_db_password();

  if($db_username!==null && $db_password!==null){
    return $username===$db_username && $password===$db_password;//return true if input data === db data
  }
  return false;//if password or username are empty in the password  
}


//functions to change admin data in the data base

//change admin username 
public function update_username($newUsername){
  $query="UPDATE admin SET username = '$newUsername' LIMIT 1";
  return $this->conn->query($query); //true if username updated successfully
}

//change admin password 
public function update_password($newPassword){
$query="UPDATE admin SET password = '$newPassword' LIMIT 1";
return $this->conn->query($query); //true if password updated successfully and
}
}
?>