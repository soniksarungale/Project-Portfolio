<?php
class ProjectDeleted{
    private $conn;
    private $table = 'project_deleted';

    public $project_deleted_id;
    public $project_id;
    public $deleted_folder;
    public $deleted_on;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET project_id = :project_id, deleted_folder = :deleted_folder';

        $stmt = $this->conn->prepare($query);

        $this->project_id = htmlspecialchars(strip_tags($this->project_id));
        $this->deleted_folder = htmlspecialchars(strip_tags($this->deleted_folder));

        $stmt->bindParam(':project_id', $this->project_id);
        $stmt->bindParam(':deleted_folder', $this->deleted_folder);

        if($stmt->execute()) {
            $this->project_deleted_id=$this->conn->lastInsertId();
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}
?>
