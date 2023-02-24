<?php
class User {
    private $conn;
    private $table = 'users';

    public $user_id;
    public $full_name;
    public $username;
    public $email;
    public $website;
    public $company;
    public $location;
    public $bio;
    public $profile_picture;
    public $password;
    public $account_level;
    public $verified;
    public $private;
    public $disabled;
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
    public function findByUsername($username)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND username = :username ORDER BY user_id DESC';

        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));

        $stmt->bindParam(':username', $username);

        $stmt->execute();

        return $stmt;
    }
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET full_name = :full_name, username = :username, password = :password, email = :email';

        $stmt = $this->conn->prepare($query);

        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = md5($this->password);
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':email', $this->email);

        if($stmt->execute()) {
            $this->user_id=$this->conn->lastInsertId();
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
    public function emailExist($email)
    {
      $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email AND active = 1';
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':email', $email);

      $result = $stmt->execute();
      if($stmt->rowCount() > 0) {
          return true;
      }
      return false;

    }
    public function uniqueUsername($username)
    {
      $query = 'SELECT * FROM ' . $this->table . ' WHERE username = :username AND active = 1';
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':username', $username);

      $result = $stmt->execute();
      if($stmt->rowCount() > 0) {
          return false;
      }
      return true;

    }
    public function verified($user_id)
    {
      $query = 'UPDATE ' . $this->table . ' SET verified = 1 WHERE user_id = :user_id';

      $stmt = $this->conn->prepare($query);

      $user_id = htmlspecialchars(strip_tags($user_id));

      $stmt->bindParam(':user_id', $user_id);

      if($stmt->execute()) {
          return true;
      }
      printf("Error: %s.\n", $stmt->error);

      return false;
    }
    public function login($email,$pass){
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND email = :email AND password = :password ORDER BY user_id DESC';

        $stmt = $this->conn->prepare($query);

        $email = htmlspecialchars(strip_tags($email));
        $password = md5($pass);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        $stmt->execute();

        return $stmt;
    }
    public function update($user_id){
      $query = 'UPDATE ' . $this->table . ' SET full_name = :full_name, website = :website, company = :company, location = :location, bio = :bio WHERE user_id = :user_id';

      $stmt = $this->conn->prepare($query);

      $user_id = htmlspecialchars(strip_tags($user_id));

      $this->full_name = htmlspecialchars(strip_tags($this->full_name));
      $this->website = strip_tags($this->website);
      $this->company = htmlspecialchars(strip_tags($this->company));
      $this->location = htmlspecialchars(strip_tags($this->location));
      $this->bio = htmlspecialchars(strip_tags($this->bio));

      $stmt->bindParam(':full_name', $this->full_name);
      $stmt->bindParam(':website', $this->website);
      $stmt->bindParam(':company', $this->company);
      $stmt->bindParam(':location', $this->location);
      $stmt->bindParam(':bio', $this->bio);
      $stmt->bindParam(':user_id', $user_id);

      if($stmt->execute()) {
          return true;
      }
      printf("Error: %s.\n", $stmt->error);

      return false;
  }
  public function updateUsername($user_id)
  {
    $query = 'UPDATE ' . $this->table . ' SET username = :username WHERE user_id = :user_id';

    $stmt = $this->conn->prepare($query);

    $user_id = htmlspecialchars(strip_tags($user_id));

    $this->username = htmlspecialchars(strip_tags($this->username));

    $stmt->bindParam(':username', $this->username);
    $stmt->bindParam(':user_id', $user_id);

    if($stmt->execute()) {
        return true;
    }
//    printf("Error: %s.\n", $stmt->error);
    return false;
  }
  public function updateProfilePicture($user_id)
  {
    $query = 'UPDATE ' . $this->table . ' SET profile_picture = :profile_picture WHERE user_id = :user_id';

    $stmt = $this->conn->prepare($query);

    $user_id = htmlspecialchars(strip_tags($user_id));

    $this->profile_picture = htmlspecialchars(strip_tags($this->profile_picture));

    $stmt->bindParam(':profile_picture', $this->profile_picture);
    $stmt->bindParam(':user_id', $user_id);

    if($stmt->execute()) {
        return true;
    }
//    printf("Error: %s.\n", $stmt->error);
    return false;
  }
}
?>
