<?php 
include ('DBconnection.php');

class ReactionModel {
  private $conn;
 
  public function __construct($conn) {
    $this->conn = $conn;
  }
 
  public function result_query($query, $params = []) {
    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);
    return $stmt;
  }

  public function reactToPost($user_id, $post_id, $reaction) {
    $query = "INSERT INTO reactions (user_id, post_id, react_type, created_at) 
              VALUES (:user_id, :post_id, :reaction, NOW()) 
              ON DUPLICATE KEY UPDATE react_type = :reaction";
    $params = [
      ':user_id' => $user_id,
      ':post_id' => $post_id,
      ':reaction' => $reaction
    ];
    return $this->result_query($query, $params);
  }

  public function get_post_reactions($post_id) {
    $query = "SELECT react_type, COUNT(*) as count FROM reactions
              WHERE post_id = :post_id GROUP BY react_type";
    $params = [':post_id' => $post_id];
    $stmt = $this->result_query($query, $params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function removeReaction($user_id, $post_id) {
    $query = "DELETE FROM reactions WHERE user_id = :user_id AND post_id = :post_id";
    $params = [
      ':user_id' => $user_id,
      ':post_id' => $post_id
    ];
    return $this->result_query($query, $params);
  }
}
?>
