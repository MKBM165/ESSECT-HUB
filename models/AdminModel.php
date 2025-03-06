<?php
include('DBconnection.php');

class AdminModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    private function result_query($query) {
        try {
            return $this->conn->query($query);
        } catch (PDOException $e) {
            echo "Query error: " . $e->getMessage();
            return false;
        }
    }

    public function get_admin_username() {
        $result = $this->result_query("SELECT username FROM admin LIMIT 1");
        return $result ? $result->fetch(PDO::FETCH_ASSOC)['username'] : null;
    }

    public function get_admin_password() {
        $result = $this->result_query("SELECT password FROM admin LIMIT 1");
        return $result ? $result->fetch(PDO::FETCH_ASSOC)['password'] : null;
    }

    public function login($username, $password) {
        $db_username = $this->get_admin_username();
        $db_password = $this->get_admin_password();

        if ($db_username !== null && $db_username === $username) {
            if ($db_password !== null && password_verify($password, $db_password)) {
                return true;
            }
        }
        return false;
    }
}
?>
