<?php
include('DBconnection.php');

class ClubModel{
  private $conn;

  public function __construct($conn){
    $this->conn = $conn;
  }
  
  private function result_query($query, $params = []) {
    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

  // ************************************************************ --  GETTERS  --***************************************************************************//
  public function get_club_info($club_id) {
    $query = "
        SELECT 
            nom,club_id,
            username, 
            password, 
            club_desc , 
            club_image , 
            email, 
            DATE_FORMAT(date_creation, '%d/%m/%y') AS date_created
        FROM club
        WHERE club_id = :club_id
    ";
    
    $stmt = $this->result_query($query, [':club_id' => $club_id]);
    $row = $stmt->fetch(PDO::FETCH_OBJ);

    return $row ? $row : null; 
}
  public function get_club_name($club_id){
    $query = "SELECT nom FROM club WHERE club_id = :club_id";
    $stmt = $this->result_query($query, [':club_id' => $club_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['nom'] : null;
  }

  public function get_club_username($club_id){
    $query = "SELECT username FROM club WHERE club_id = :club_id";
    $stmt = $this->result_query($query, [':club_id' => $club_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['username'] : null;
  }

  public function get_club_password($club_id){
    $query = "SELECT password FROM club WHERE club_id = :club_id";
    $stmt = $this->result_query($query, [':club_id' => $club_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['password'] : null;
  }

 public function get_club_id($username){
  $stmt = $this->result_query("SELECT club_id FROM club WHERE username =?",[$username]);
  $club=$stmt ->fetch(PDO::FETCH_ASSOC);
  return $club ? $club ['club_id']:null;
 }

  public function get_club_desc($club_id){
    $query = "SELECT club_desc FROM club WHERE club_id = :club_id";
    $stmt = $this->result_query($query, [':club_id' => $club_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['club_desc'] : null;
  }

  public function get_club_img($club_id){
    $query = "SELECT club_image FROM club WHERE club_id = :club_id";
    $stmt = $this->result_query($query, [':club_id' => $club_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['club_image'] : null;
  }

  public function get_club_email($club_id){
    $query = "SELECT email FROM club WHERE club_id = :club_id";
    $stmt = $this->result_query($query, [':club_id' => $club_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['email'] : null;
  }

  public function get_club_date($club_id){
    $query = "SELECT date_creation FROM club WHERE club_id = :club_id";
    $stmt = $this->result_query($query, [':club_id' => $club_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $date = new DateTime($row['date_creation']);
      return $date->format('d/m/y');
    }
    return null;
  }

  public function get_all_clubs(){
    $query = "SELECT * FROM club";
    $stmt = $this->result_query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // ************************************************************ --  AUTHENTIFICATION  --***************************************************************************//

  public function login($username,$password){
    $stmt = $this->result_query("SELECT club_id , password FROM club WHERE username = ?",[$username]);
    $result = $stmt->fetch(PDO ::FETCH_ASSOC);

    if ($result && password_verify($password,$result['password'])){
      return true;
    }

    return false;
    
  }

  // ************************************************************ --  ENABLE MODIFICATION  --***************************************************************************//

  public function change_username($club_id, $new_username){
    $query = "UPDATE club SET username = :new_username WHERE club_id = :club_id";
    return $this->result_query($query, [':new_username' => $new_username, ':club_id' => $club_id]);
  }

  public function change_password($club_id, $new_password){
    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE club SET password = :password WHERE club_id = :club_id";
    return $this->result_query($query, [':password' => $hashedPassword, ':club_id' => $club_id]);
  }

  public function create_club($nom, $username, $club_desc, $club_image, $password, $email){
    $query = "INSERT INTO club (nom, username, club_desc, club_image, password, email, date_creation) 
              VALUES (:nom, :username, :club_desc, :club_image, :password, :email, NOW())";
    return $this->result_query($query, [':nom' => $nom, ':username' => $username, ':club_desc' => $club_desc, ':club_image' => $club_image, ':password' => password_hash($password, PASSWORD_DEFAULT), ':email' => $email]);
  }

  public function delete_club($club_id){
    $query = "SELECT club_id FROM club WHERE club_id = :club_id";
    $stmt = $this->result_query($query, [':club_id' => $club_id]);
    if ($stmt->rowCount() > 0) {
      $query = "DELETE FROM club WHERE club_id = :club_id";
      return $this->result_query($query, [':club_id' => $club_id]);
    }
    return false;
  }

  // image upload
  public function uploadClubImage($clubId) {
    $targetDir = "../assets/uploads/";
    $fileName = time() . "_" . basename($_FILES['image']['name']);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
        $imagePath = "assets/uploads/" . $fileName;
        $query = "UPDATE club SET club_image = :club_image WHERE club_id = :club_id";
        $this->result_query($query, [':club_image' => $imagePath, ':club_id' => $clubId]);
        return ["status" => "success", "image_url" => $imagePath];
    } else {
        return ["status" => "error", "message" => "Upload failed"];
    }
  }
}
?>
