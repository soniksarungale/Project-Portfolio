<?php
class ProjectArchive {
    private $conn;
    private $table = 'project_archives';

    public $project_archive_id;
    public $project_id;
    public $file_name;
    public $archive_type;
    public $archive_size;
    public $files_count;
    public $created_on;
    public $modified_on;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function find($project_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND project_id = :project_id ORDER BY project_archive_id DESC';

        $stmt = $this->conn->prepare($query);

        $project_id = htmlspecialchars(strip_tags($project_id));

        $stmt->bindParam(':project_id', $project_id);

        $stmt->execute();

        return $stmt;
    }
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET project_id = :project_id, archive_type = :archive_type, file_name = :file_name, archive_size = :archive_size, files_count = :files_count';

        $stmt = $this->conn->prepare($query);

        $this->project_id = htmlspecialchars(strip_tags($this->project_id));
        $this->archive_type = strip_tags($this->archive_type);
        $this->file_name = htmlspecialchars(strip_tags($this->file_name));
        $this->archive_size = htmlspecialchars(strip_tags($this->archive_size));
        $this->files_count = htmlspecialchars(strip_tags($this->files_count));

        $stmt->bindParam(':project_id', $this->project_id);
        $stmt->bindParam(':archive_type', $this->archive_type);
        $stmt->bindParam(':file_name', $this->file_name);
        $stmt->bindParam(':archive_size', $this->archive_size);
        $stmt->bindParam(':files_count', $this->files_count);

        if($stmt->execute()) {
            $this->project_archive_id=$this->conn->lastInsertId();
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}
?>
