<?php
class DB {  
    public function is_table_empty() {
        $db = new DbConnect;
		$conn = $db->connect();
        $stmt = $conn->prepare("SELECT id FROM google_oauth WHERE provider = 'google'");
		$stmt->execute();
        if($stmt->num_rows) {
            return false;
        }
  
        return true;
    }
  
    public function get_access_token() {
        $db = new DbConnect;
		$conn = $db->connect();
        $stmt = $conn->prepare("SELECT provider_value FROM google_oauth WHERE provider = 'google'");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_decode($result['provider_value']);
    }
  
    public function get_refersh_token() {
        $result = $this->get_access_token();
        return $result->refresh_token;
    }
  
    public function update_access_token($token) {
        $sql = "";
        if($this->is_table_empty()) {
            $sql = "INSERT INTO google_oauth(provider, provider_value) VALUES('google', '$token')";
        } else {
            $sql = "UPDATE google_oauth SET provider_value = '$token' WHERE provider = 'google'";
        }
        $db = new DbConnect;
        $conn = $db->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
}