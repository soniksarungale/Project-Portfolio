<?php
class Visitor {
    private $conn;
    private $table = 'visitors';

    public $visitor_id;
    public $ip_address;
    public $city;
    public $state;
    public $country;
    public $browser;
    public $browser_version;
    public $os;
    public $device;
    public $page;
    public $url;
    public $reference;
    public $visited_at;
    public $modified_on;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function find($visitor_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE active=1 AND visitor_id = :visitor_id ORDER BY visitor_id DESC';

        $stmt = $this->conn->prepare($query);

        $visitor_id = htmlspecialchars(strip_tags($visitor_id));

        $stmt->bindParam(':visitor_id', $visitor_id);

        $stmt->execute();

        return $stmt;
    }
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET ip_address = :ip_address, city = :city, state = :state, country = :country, browser = :browser, browser_version = :browser_version, os = :os, device = :device, page = :page, url = :url, reference = :reference';

        $stmt = $this->conn->prepare($query);

        $this->page = htmlspecialchars(strip_tags($this->page));

        $stmt->bindParam(':ip_address', $this->ip_address);
        $stmt->bindParam(':city', $this->city);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':browser', $this->browser);
        $stmt->bindParam(':browser_version', $this->browser_version);
        $stmt->bindParam(':os', $this->os);
        $stmt->bindParam(':device', $this->device);
        $stmt->bindParam(':page', $this->page);
        $stmt->bindParam(':url', $this->url);
        $stmt->bindParam(':reference', $this->reference);

        if($stmt->execute()) {
            $this->visitor_id=$this->conn->lastInsertId();
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}
?>
