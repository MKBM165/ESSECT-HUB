<?php
include('DBconnection.php');

class AdminModel{
  private $conn;

  public function __construct($conn){
    $this->conn=$conn;
  }

public function get_db_username(){
  $query="SELECT username FROM admin LIMIT 1";  //get the admin username from admin table 
  $result=$this->conn->query($query);  //store the query result in $result variable
 
  if ($result->num_rows===1){  //if the result has only one row that means the table is valid 
    $row =$result->fetch_assoc();
    return $row['username'];//return username in form of a string
  }
}


 public function get_db_password(){
   $query="SELECT password FROM admin LIMIT 1"; //get the admin password from admin table 
   $result=$this->conn->query($query); //store the query result in $result variable

   if($result -> num_rows===1){//if the result has only one row that means the table is valid 
    $row=$result->fetch_assoc();//store thhe result as an assosiative array in $row variable
    return $row['password'];//return password in form of a string
   }
   return null;
 }

}
?>