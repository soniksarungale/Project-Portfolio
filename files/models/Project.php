<?php
date_default_timezone_set("Asia/Kolkata");
class Project{
  private $conn;
  private $table = 'projects';

  public $project_id;
  public $user_id;
  public $title;
  public $url;
  public $project_type_id;
  public $description;
  public $public;
  public $uploaded;
  public $folder_name;
  public $created_on;
  public $modified_on;
  public $active;

  public function __construct($db) {
      $this->conn = $db;
  }
  public function allPublic()
  {
      $query = 'SELECT p.*,(SELECT type FROM project_types AS pt WHERE pt.project_type_id=p.project_type_id AND pt.active=1) AS project_type,(SELECT count(*) AS views FROM project_visitors AS pv WHERE pv.project_id = p.project_id) AS views, (SELECT username FROM users AS u WHERE u.user_id=p.user_id AND u.active=1 AND u.disabled=0) AS username FROM projects AS p WHERE active=1 AND public=1 AND uploaded=1 ORDER BY project_id DESC';

      $stmt = $this->conn->prepare($query);

      $stmt->execute();

      return $stmt;
  }

  public function findByUser($user_id)
  {
      $query = 'SELECT *,(SELECT type FROM project_types AS pt WHERE pt.project_type_id=p.project_type_id) AS project_type FROM ' . $this->table . ' AS p WHERE active=1 AND user_id = :user_id ORDER BY project_id DESC';

      $stmt = $this->conn->prepare($query);

      $user_id = htmlspecialchars(strip_tags($user_id));

      $stmt->bindParam(':user_id', $user_id);

      $stmt->execute();

      return $stmt;
  }
  public function findByUserPublic($user_id)
  {
      $query = 'SELECT *,(SELECT type FROM project_types AS pt WHERE pt.project_type_id=p.project_type_id) AS project_type FROM ' . $this->table . ' AS p WHERE active=1 AND user_id = :user_id AND public=1 ORDER BY project_id DESC';

      $stmt = $this->conn->prepare($query);

      $user_id = htmlspecialchars(strip_tags($user_id));

      $stmt->bindParam(':user_id', $user_id);

      $stmt->execute();

      return $stmt;
  }
  public function findByUrl($username,$url)
  {
      $query = 'SELECT p.*,u.* FROM ' . $this->table . ' AS p, users AS u WHERE p.active=1 AND u.active=1 AND p.user_id=u.user_id AND p.url = :url AND u.username=:username';

      $stmt = $this->conn->prepare($query);

      $url = htmlspecialchars(strip_tags($url));
      $username = htmlspecialchars(strip_tags($username));

      $stmt->bindParam(':url', $url);
      $stmt->bindParam(':username', $username);

      $stmt->execute();

      return $stmt;
  }
  public function create() {
      $query = 'INSERT INTO ' . $this->table . ' SET user_id = :user_id, title = :title, url = :url, project_type_id = :project_type_id, description = :description, public = :public, folder_name= :folder_name';

      $stmt = $this->conn->prepare($query);

      $this->user_id = htmlspecialchars(strip_tags($this->user_id));
      $this->title = htmlspecialchars(strip_tags($this->title));
      $this->url = strip_tags($this->url);
      $this->project_type_id = htmlspecialchars(strip_tags($this->project_type_id));
      $this->description = htmlspecialchars(strip_tags($this->description));
      $this->public = htmlspecialchars(strip_tags($this->public));
      $this->folder_name = htmlspecialchars(strip_tags($this->folder_name));

      $stmt->bindParam(':user_id', $this->user_id);
      $stmt->bindParam(':title', $this->title);
      $stmt->bindParam(':url', $this->url);
      $stmt->bindParam(':project_type_id', $this->project_type_id);
      $stmt->bindParam(':description', $this->description);
      $stmt->bindParam(':public', $this->public);
      $stmt->bindParam(':folder_name', $this->folder_name);

      if($stmt->execute()) {
          $this->project_id=$this->conn->lastInsertId();
          return true;
      }

      printf("Error: %s.\n", $stmt->error);

      return false;
  }
  public function isProjectExist($url)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE url = :url AND active = 1 AND user_id = :user_id';
    $stmt = $this->conn->prepare($query);

    $url = strip_tags($url);
    $this->user_id = htmlspecialchars(strip_tags($this->user_id));

    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':user_id', $this->user_id);

    $result = $stmt->execute();
    if($stmt->rowCount() > 0) {
        return true;
    }
    return false;

  }
  public function uploaded($project_id)
  {
    $query = 'UPDATE ' . $this->table . ' SET uploaded = 1 WHERE project_id = :project_id';

    $stmt = $this->conn->prepare($query);

    $project_id = htmlspecialchars(strip_tags($project_id));

    $stmt->bindParam(':project_id', $project_id);

    if($stmt->execute()) {
        return true;
    }
    printf("Error: %s.\n", $stmt->error);

    return false;
  }
  public function notUploaded($project_id)
  {
    $query = 'UPDATE ' . $this->table . ' SET uploaded = 0 WHERE project_id = :project_id';

    $stmt = $this->conn->prepare($query);

    $project_id = htmlspecialchars(strip_tags($project_id));

    $stmt->bindParam(':project_id', $project_id);

    if($stmt->execute()) {
        return true;
    }
    printf("Error: %s.\n", $stmt->error);

    return false;
  }
  public function update($project_id)
  {
    $query = 'UPDATE ' . $this->table . ' SET title = :title, url = :url, description = :description, public = :public WHERE project_id = :project_id';

    $stmt = $this->conn->prepare($query);

    $this->title = htmlspecialchars(strip_tags($this->title));
    $this->url = htmlspecialchars(strip_tags($this->url));
    $this->description = strip_tags($this->description);
    $this->public = htmlspecialchars(strip_tags($this->public));

    $project_id = htmlspecialchars(strip_tags($project_id));

    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':url', $this->url);
    $stmt->bindParam(':description', $this->description);
    $stmt->bindParam(':public', $this->public);
    $stmt->bindParam(':project_id', $project_id);

    if($stmt->execute()) {
        return true;
    }
    printf("Error: %s.\n", $stmt->error);

    return false;
  }
  public function delete($project_id)
  {
    $query = 'UPDATE ' . $this->table . ' SET active = 0 WHERE project_id = :project_id';

    $stmt = $this->conn->prepare($query);

    $project_id = htmlspecialchars(strip_tags($project_id));

    $stmt->bindParam(':project_id', $project_id);

    if($stmt->execute()) {
        return true;
    }
    printf("Error: %s.\n", $stmt->error);

    return false;
  }
}
?>
