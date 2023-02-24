<?php
class Skill {
    private $conn;
    private $table = 'skills';

    public $skill_id;
    public $name;
    public $skill_type_id;
    public $created_on;
    public $modified_on;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function find($skill_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND skill_id = :skill_id ORDER BY skill_id DESC';

        $stmt = $this->conn->prepare($query);

        $skill_id = htmlspecialchars(strip_tags($skill_id));

        $stmt->bindParam(':skill_id', $skill_id);

        $stmt->execute();

        return $stmt;
    }
    public function all()
    {
        $query = 'SELECT *,(SELECT type FROM skill_types WHERE skill_type_id=skills.skill_type_id) AS skill_type FROM ' . $this->table . ' WHERE active=1 ORDER BY skill_id ASC';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }
}
?>
