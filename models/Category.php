<?php 
class Category {
    // DB Properties
    private $conn;
    private $table = 'categories';

    // Category Properties
    public $id;
    public $category;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get All Categories
    public function read() {
        // Create query
        $query = 'SELECT
                    id,
                    category
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

    // Get Single Category
    public function read_single() {
        // Create query
        $query = 'SELECT
                    id,
                    category
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
            $this->category = $row['category'];
        }
    }

    // Create Category
    public function create() {
        // Create Query
        $query = 'INSERT INTO ' . $this->table . ' (category)
                  VALUES (:category)';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind data
        $stmt->bindParam(':category', $this->category);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        $errorInfo = $stmt->errorInfo();
        echo "Error: " . $errorInfo[2] . ".\n";
        return false;
    }

    // Update Category
    public function update() {
        // Check if the ID exists before updating
        $check_query = 'SELECT id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $check_stmt->execute();
    
        if ($check_stmt->rowCount() == 0) {
            return "not_found"; // ID does not exist
        }
    
        // Create Query
        $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';
        
        // Prepare Statement
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind data
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        
        // Execute query
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
    

    // Delete Category
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