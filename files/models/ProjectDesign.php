<?php
class ProjectDesign {
    private $conn;
    private $table = 'project_designs';

    public $project_design_id;
    public $project_id;
    public $name;
    public $file_name;
    public $orientation;
    public $created_on;
    public $modified_on;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function find($project_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND project_id = :project_id ORDER BY project_design_id DESC';

        $stmt = $this->conn->prepare($query);

        $project_id = htmlspecialchars(strip_tags($project_id));

        $stmt->bindParam(':project_id', $project_id);

        $stmt->execute();

        return $stmt;
    }
    public function findThumbnail($project_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND project_id = :project_id  ORDER BY FIELD(orientation,"landscape","square","portrait"), project_design_id DESC LIMIT 1';

        $stmt = $this->conn->prepare($query);

        $project_id = htmlspecialchars(strip_tags($project_id));

        $stmt->bindParam(':project_id', $project_id);

        $stmt->execute();

        return $stmt;
    }
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET project_id = :project_id, name = :name, file_name = :file_name, orientation = :orientation';

        $stmt = $this->conn->prepare($query);

        $this->project_id = htmlspecialchars(strip_tags($this->project_id));
        $this->name = strip_tags($this->name);
        $this->file_name = htmlspecialchars(strip_tags($this->file_name));
        $this->orientation = htmlspecialchars(strip_tags($this->orientation));

        $stmt->bindParam(':project_id', $this->project_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':file_name', $this->file_name);
        $stmt->bindParam(':orientation', $this->orientation);

        if($stmt->execute()) {
            $this->project_design_id=$this->conn->lastInsertId();
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
    public function delete($project_design_id)
    {
      $query = 'UPDATE ' . $this->table . ' SET active = 0 WHERE project_design_id = :project_design_id';

      $stmt = $this->conn->prepare($query);

      $project_design_id = htmlspecialchars(strip_tags($project_design_id));

      $stmt->bindParam(':project_design_id', $project_design_id);

      if($stmt->execute()) {
          return true;
      }
      printf("Error: %s.\n", $stmt->error);

      return false;
    }
}
?>
