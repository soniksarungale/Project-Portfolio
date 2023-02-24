<?php
class ProjectVisitor {
    private $conn;
    private $table = 'project_visitors';

    public $project_visitors_id;
    public $project_id;
    public $visitor_id;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function find($project_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND project_id = :project_id ORDER BY project_visitors_id DESC';

        $stmt = $this->conn->prepare($query);

        $project_id = htmlspecialchars(strip_tags($project_id));

        $stmt->bindParam(':project_id', $project_id);

        $stmt->execute();

        return $stmt;
    }
    public function views($project_id)
    {
        $query = 'SELECT count(*) AS views FROM ' . $this->table . ' WHERE project_id = :project_id';

        $stmt = $this->conn->prepare($query);

        $project_id = htmlspecialchars(strip_tags($project_id));

        $stmt->bindParam(':project_id', $project_id);

        $stmt->execute();

        return $stmt;
    }
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET project_id = :project_id, visitor_id = :visitor_id';

        $stmt = $this->conn->prepare($query);

        $this->project_id = htmlspecialchars(strip_tags($this->project_id));
        $this->visitor_id = htmlspecialchars(strip_tags($this->visitor_id));

        $stmt->bindParam(':project_id', $this->project_id);
        $stmt->bindParam(':visitor_id', $this->visitor_id);

        if($stmt->execute()) {
            $this->project_visitors_id=$this->conn->lastInsertId();
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }

}
?>
