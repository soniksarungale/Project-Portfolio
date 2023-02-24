<?php
class UserSkill {
    private $conn;
    private $table = 'user_skills';

    public $user_skill_id;
    public $user_id;
    public $skill_id;
    public $created_on;
    public $modified_on;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function find($user_skill_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND user_skill_id = :user_skill_id ORDER BY user_skill_id DESC';

        $stmt = $this->conn->prepare($query);

        $user_skill_id = htmlspecialchars(strip_tags($user_skill_id));

        $stmt->bindParam(':user_skill_id', $user_skill_id);

        $stmt->execute();

        return $stmt;
    }
    public function findByUser($user_id)
    {
        $query = 'SELECT us.*,s.name,st.type FROM user_skills AS us, skills AS s, skill_types AS st WHERE us.active=1 AND s.active=1 AND st.active=1 AND us.skill_id=s.skill_id AND s.skill_type_id=st.skill_type_id AND us.user_id = :user_id ORDER BY us.user_skill_id DESC';

        $stmt = $this->conn->prepare($query);

        $user_id = htmlspecialchars(strip_tags($user_id));

        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        return $stmt;
    }
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET user_id = :user_id, skill_id = :skill_id';

        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->skill_id = htmlspecialchars(strip_tags($this->skill_id));

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':skill_id', $this->skill_id);

        if($stmt->execute()) {
            $this->user_skill_id=$this->conn->lastInsertId();
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
    public function delete($user_skill_id)
    {
      $query = 'UPDATE ' . $this->table . ' SET active = 0 WHERE user_skill_id = :user_skill_id';

      $stmt = $this->conn->prepare($query);

      $user_skill_id = htmlspecialchars(strip_tags($user_skill_id));

      $stmt->bindParam(':user_skill_id', $user_skill_id);

      if($stmt->execute()) {
          return true;
      }
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

}
?>
