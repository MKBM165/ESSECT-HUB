<?php 
include ('DBconnection.php');
class ReactionModel {
  private $conn;
 
  public function __construct($conn) {
   $this->conn = $conn;
  }
 
  public function result_query($query) {
   return $this->conn->query($query);
  }
 
  public function reactToPost($user_id, $post_id, $reaction) {
   $result = $this->result_query("INSERT INTO reactions (user_id, post_id, react_type, created_at) 
                                 VALUES ($user_id, $post_id, '$reaction', NOW()) 
                                 ON DUPLICATE KEY UPDATE react_type = '$reaction'");
   return $result;
  }
 
  public function get_post_reactions($post_id) {
   $result = $this->result_query("SELECT react_type, COUNT(*) as count FROM reactions
                                 WHERE post_id = $post_id GROUP BY react_type");
   return $result->fetch_all(MYSQLI_ASSOC);
  }
 }
 
?>