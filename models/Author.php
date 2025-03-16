<?php 
class Author {
    // DB Properties
    private $conn;
    private $table = 'authors';

    // Author Properties
    public $id;
    public $author;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get All Authors
    public function read() {
        // Create query
        $query = 'SELECT
                    id,
                    author
                  FROM
                    ' . $this->table . '
                  ORDER BY
                    id DESC';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);
    
        // Execute query
        $stmt->execute();
    
        return $stmt;
    }

    // Get Single Author
    public function read_single() {
        // Create query
        $query = 'SELECT
                    id,
                    author
                  FROM
                    ' . $this->table . '
                  WHERE id = ?
                  LIMIT 1';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);
    
        // Bind ID
        $stmt->bindParam(1, $this->id);
    
        // Execute query
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Set properties
        if ($row) {
            $this->id = $row['id'];
            $this->author = $row['author'];
        }
    }

    // Create Author
    public function create() {
        // Create Query
        $query = 'INSERT INTO ' . $this->table . ' (author)
                  VALUES (:author)';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->author = htmlspecialchars(strip_tags($this->author));

        // Bind data
        $stmt->bindParam(':author', $this->author);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        $errorInfo = $stmt->errorInfo();
        echo "Error: " . $errorInfo[2] . ".\n";
        return false;
    }

    // Update Author
    public function update() {
        // Check if the ID exists before updating
        $check_query = 'SELECT id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $check_stmt->execute();
    
        if ($check_stmt->rowCount() == 0) {
            return "not_found"; // ID does not exist
        }
    
        // Create Update Query
        $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';
        
        // Prepare Statement
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind data
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        
        // Execute query
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
    

    // Delete Author
    public function delete() {
        // Check if the ID exists before attempting deletion
        $check_query = 'SELECT id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $check_stmt->execute();
    
        // Fetch result
        if ($check_stmt->rowCount() == 0) {
            return "not_found"; // ID does not exist
        }
    
        // Create delete query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
    
        // Bind ID
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    
        // Execute query
        if ($stmt->execute()) {
            return true;
        }
    
        // If something goes wrong
        return false;
    }
}
?>