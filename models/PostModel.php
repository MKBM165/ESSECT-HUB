<?php
include('DBconnection.php');

class PostModel {
  private $conn;
 
  public function __construct($conn) {
   $this->conn = $conn;
  }
 
  public function result_query($query) {
   return $this->conn->query($query);
  }
 
  public function createPost($club_id, $caption, $image) {
   $result = $this->result_query("INSERT INTO posts (club_id, caption, image, created_at) 
                                  VALUES ($club_id, '$caption', '$image', NOW())");
   return $result;
  }
 
  public function deletePost($post_id) {
   $result = $this->result_query("DELETE FROM posts WHERE post_id = $post_id");
   return $result;
  }
 
  public function get_club_posts($club_id) {
   $result = $this->result_query("SELECT * FROM posts WHERE club_id = $club_id ORDER BY created_at DESC");
   return $result ? $result->fetch_all(MYSQLI_ASSOC) : []; // empty array if no values found
  }
 
  public function get_user_feed($user_id) {
   $result = $this->result_query("SELECT posts.* FROM posts
                                 JOIN member ON posts.club_id = member.club_id
                                 WHERE member.user_id = $user_id
                                 ORDER BY posts.created_at DESC");
   return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
  }
 }
 

?>