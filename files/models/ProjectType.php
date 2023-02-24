<?php
class ProjectType {
    private $conn;
    private $table = 'project_types';

    public $project_type_id;
    public $type;
    public $active;
    public $created_on;
    public $modified_on;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function find($project_type_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND project_type_id = :project_type_id ORDER BY project_type_id DESC';

        $stmt = $this->conn->prepare($query);

        $project_type_id = htmlspecialchars(strip_tags($project_type_id));

        $stmt->bindParam(':project_type_id', $project_type_id);

        $stmt->execute();

        return $stmt;
    }
    public function all()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 ORDER BY project_type_id ASC';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }
}
?>
