<?php

class Verification
{
    private $conn;
    private $table = 'verifications';

    public $verification_id;
    public $user_id;
    public $code;
    public $expire_on;
    public $created_on;
    public $verified_on;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function all()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 ORDER BY verification_id DESC';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }
    public function find($verification_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE verification_id = :verification_id ORDER BY verification_id DESC';

        $stmt = $this->conn->prepare($query);

        $verification_id = htmlspecialchars(strip_tags($verification_id));

        $stmt->bindParam(':verification_id', $verification_id);

        $stmt->execute();

        return $stmt;
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET user_id = :user_id, code = :code, expire_on = :expire_on';

        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':code', $this->code);
        $stmt->bindParam(':expire_on', $this->expire_on);

        if($stmt->execute()) {
            $this->verification_id=$this->conn->lastInsertId();
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
    public function findLatestByCode($code){
        $query = 'SELECT * FROM ' . $this->table . ' WHERE code = :code ORDER BY verification_id DESC LIMIT 1';

        $stmt = $this->conn->prepare($query);

        $code = htmlspecialchars(strip_tags($code));

        $stmt->bindParam(':code', $code);

        $stmt->execute();

        return $stmt;
    }
}
