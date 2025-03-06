<?php
include('DBconnection.php');

class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Reusable function for prepared queries
    private function result_query($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    // ************************************************************ -- GETTERS -- *************************************************************************** //

    public function get_user_profile($user_id) {
        $stmt = $this->result_query("SELECT user_id, username, nom, prenom, cv FROM users WHERE user_id = ?", [$user_id]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($profile) {
            $clubsStmt = $this->result_query("SELECT c.club_id, c.nom FROM member m JOIN club c ON m.club_id = c.club_id WHERE m.user_id = ?", [$user_id]);
            $profile['clubs'] = $clubsStmt->fetchAll(PDO::FETCH_ASSOC);
            return $profile;
        }
        return null;
    }

    public function get_user_feed($user_id) {
        $stmt = $this->result_query("SELECT club_id FROM member WHERE user_id = ?", [$user_id]);
        $clubIds = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clubIds[] = $row['club_id'];
        }

        if (empty($clubIds)) {
            return [];
        }

        $clubIdsString = implode(',', $clubIds);
        $stmt = $this->result_query("SELECT p.post_id, p.club_id, c.nom AS club_name, p.caption, p.image, p.upload_date
                                     FROM post p
                                     JOIN club c ON p.club_id = c.club_id
                                     WHERE p.club_id IN ($clubIdsString)
                                     ORDER BY p.upload_date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ************************************************************ -- AUTHENTICATION -- *************************************************************************** //

    public function login($username, $password) {
        $stmt = $this->result_query("SELECT user_id, password FROM users WHERE username = ?", [$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($password, $result['password'])) {
            return true;
        }
        return false;
    }

    // ************************************************************ -- ENABLE MODIFICATION -- *************************************************************************** //

    public function change_username($user_id, $new_username) {
        $stmt = $this->result_query("UPDATE users SET username = ? WHERE user_id = ?", [$new_username, $user_id]);
        return $stmt->rowCount() > 0;
    }

    public function change_password($user_id, $new_password) {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->result_query("UPDATE users SET password = ? WHERE user_id = ?", [$hashedPassword, $user_id]);
        return $stmt->rowCount() > 0;
    }

    public function change_nom($user_id, $new_nom) {
        $stmt = $this->result_query("UPDATE users SET nom = ? WHERE user_id = ?", [$new_nom, $user_id]);
        return $stmt->rowCount() > 0;
    }

    public function change_prenom($user_id, $new_prenom) {
        $stmt = $this->result_query("UPDATE users SET prenom = ? WHERE user_id = ?", [$new_prenom, $user_id]);
        return $stmt->rowCount() > 0;
    }

    public function change_cv($user_id, $new_cv) {
        $stmt = $this->result_query("UPDATE users SET cv = ? WHERE user_id = ?", [$new_cv, $user_id]);
        return $stmt->rowCount() > 0;
    }

    public function create_user($username, $nom, $prenom, $cv, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->result_query("INSERT INTO users (username, nom, prenom, cv, password) VALUES (?, ?, ?, ?, ?)",
                                    [$username, $nom, $prenom, $cv, $hashedPassword]);
        return $stmt->rowCount() > 0;
    }
}
?>
