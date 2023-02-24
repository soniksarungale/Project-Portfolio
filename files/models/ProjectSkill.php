<?php
class ProjectSkill {
    private $conn;
    private $table = 'project_skills';

    public $project_skill_id;
    public $project_type_id;
    public $skill_id;
    public $active;
    public $created_on;
    public $modified_on;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function find($project_skill_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND project_skill_id = :project_skill_id ORDER BY project_skill_id DESC';

        $stmt = $this->conn->prepare($query);

        $project_skill_id = htmlspecialchars(strip_tags($project_skill_id));

        $stmt->bindParam(':project_skill_id', $project_skill_id);

        $stmt->execute();

        return $stmt;
    }
    public function findByType($project_type_id)
    {
        $query = 'SELECT ps.*,s.name FROM ' . $this->table . ' AS ps, skills AS s WHERE ps.active=1 AND s.active=1 AND project_type_id = :project_type_id AND s.skill_id=ps.skill_id ORDER BY project_type_id DESC';

        $stmt = $this->conn->prepare($query);

        $project_type_id = htmlspecialchars(strip_tags($project_type_id));

        $stmt->bindParam(':project_type_id', $project_type_id);

        $stmt->execute();

        return $stmt;
    }
    public function all()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 ORDER BY project_skill_id ASC';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }
}
?>
