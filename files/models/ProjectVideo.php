<?php
class ProjectVideo {
    private $conn;
    private $table = 'project_videos';

    public $project_video_id;
    public $project_id;
    public $link;
    public $video_id;
    public $created_on;
    public $modified_on;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function find($project_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND project_id = :project_id ORDER BY project_video_id DESC';

        $stmt = $this->conn->prepare($query);

        $project_id = htmlspecialchars(strip_tags($project_id));

        $stmt->bindParam(':project_id', $project_id);

        $stmt->execute();

        return $stmt;
    }
    public function findThumbnail($project_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND project_id = :project_id  ORDER BY  project_video_id DESC LIMIT 1';

        $stmt = $this->conn->prepare($query);

        $project_id = htmlspecialchars(strip_tags($project_id));

        $stmt->bindParam(':project_id', $project_id);

        $stmt->execute();

        return $stmt;
    }
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET project_id = :project_id, link = :link, video_id = :video_id';

        $stmt = $this->conn->prepare($query);

        $this->project_id = htmlspecialchars(strip_tags($this->project_id));
        $this->link = strip_tags($this->link);
        $this->video_id = htmlspecialchars(strip_tags($this->video_id));

        $stmt->bindParam(':project_id', $this->project_id);
        $stmt->bindParam(':link', $this->link);
        $stmt->bindParam(':video_id', $this->video_id);

        if($stmt->execute()) {
            $this->project_video_id=$this->conn->lastInsertId();
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
    public function delete($project_video_id)
    {
      $query = 'UPDATE ' . $this->table . ' SET active = 0 WHERE project_video_id = :project_video_id';

      $stmt = $this->conn->prepare($query);

      $project_video_id = htmlspecialchars(strip_tags($project_video_id));

      $stmt->bindParam(':project_video_id', $project_video_id);

      if($stmt->execute()) {
          return true;
      }
      printf("Error: %s.\n", $stmt->error);

      return false;
    }
}
?>
