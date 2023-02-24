<?php
class SocialMedia {
    private $conn;
    private $table = 'social_media';

    public $social_media_id;
    public $user_id;
    public $github;
    public $linkedin;
    public $twitter;
    public $codepen;
    public $created_on;
    public $modified_on;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function find($user_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND user_id = :user_id ORDER BY user_id DESC';

        $stmt = $this->conn->prepare($query);

        $user_id = htmlspecialchars(strip_tags($user_id));

        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        return $stmt;
    }
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET user_id = :user_id';

        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        $stmt->bindParam(':user_id', $this->user_id);

        if($stmt->execute()) {
            $this->user_id=$this->conn->lastInsertId();
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
    public function update($user_id){
      $query = 'UPDATE ' . $this->table . ' SET github = :github, linkedin = :linkedin, twitter = :twitter, codepen = :codepen WHERE user_id = :user_id';

      $stmt = $this->conn->prepare($query);

      $user_id = htmlspecialchars(strip_tags($user_id));

      $this->github = htmlspecialchars(strip_tags($this->github));
      $this->linkedin = strip_tags($this->linkedin);
      $this->twitter = htmlspecialchars(strip_tags($this->twitter));
      $this->codepen = htmlspecialchars(strip_tags($this->codepen));

      $stmt->bindParam(':github', $this->github);
      $stmt->bindParam(':linkedin', $this->linkedin);
      $stmt->bindParam(':twitter', $this->twitter);
      $stmt->bindParam(':codepen', $this->codepen);
      $stmt->bindParam(':user_id', $user_id);

      if($stmt->execute()) {
          return true;
      }
      printf("Error: %s.\n", $stmt->error);

      return false;
  }
}
?>
