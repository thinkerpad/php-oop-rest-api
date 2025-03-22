<?php 
    class Quote {
        // DB Properties
        private $conn;
        private $table = 'quotes';

        // Quote Properties
        public $id;
        public $quote;
        public $author_id;
        public $author;
        public $category_id;
        public $category;

        public function __construct($db) {
            $this->conn = $db;
        }

        // Get All Quotes
        public function read() {
            // Create query
            $query = 'SELECT 
                        q.id AS quote_id, 
                        q.quote, 
                        a.id AS author_id, 
                        a.author, 
                        c.id AS category_id, 
                        c.category
                      FROM ' . $this->table . ' q
                      JOIN authors a ON q.author_id = a.id
                      JOIN categories c ON q.category_id = c.id
                      ORDER BY q.id DESC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single Quote
        public function read_single() {
            // Create query
            $query = 'SELECT 
                        q.id AS quote_id, 
                        q.quote, 
                        a.id AS author_id, 
                        a.author, 
                        c.id AS category_id, 
                        c.category
                      FROM ' . $this->table . ' q
                      JOIN authors a ON q.author_id = a.id
                      JOIN categories c ON q.category_id = c.id
                      WHERE q.id = :id
                      LIMIT 1';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

            // Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            if ($row) {
                $this->id = $row['quote_id'];
                $this->quote = $row['quote'];
                $this->author_id = $row['author_id'];
                $this->author = $row['author'];
                $this->category_id = $row['category_id'];
                $this->category = $row['category'];
            } else {
                $this->id = null;
                $this->quote = null;
            }
        }

        // Get Quotes by Author
        public function read_by_author() {
            // Create query
            $query = 'SELECT 
                        q.id AS quote_id, 
                        q.quote, 
                        a.id AS author_id, 
                        a.author, 
                        c.id AS category_id, 
                        c.category
                      FROM ' . $this->table . ' q
                      JOIN authors a ON q.author_id = a.id
                      JOIN categories c ON q.category_id = c.id
                      WHERE q.author_id = :author_id
                      ORDER BY q.id DESC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind parameter
            $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Quotes by Category
        public function read_by_category() {
            // Create query
            $query = 'SELECT 
                        q.id AS quote_id, 
                        q.quote, 
                        a.id AS author_id, 
                        a.author, 
                        c.id AS category_id, 
                        c.category
                      FROM ' . $this->table . ' q
                      JOIN authors a ON q.author_id = a.id
                      JOIN categories c ON q.category_id = c.id
                      WHERE q.category_id = :category_id
                      ORDER BY q.id DESC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind parameter
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Quotes by Author and Category
        public function read_by_author_and_category() {
            // Create query
            $query = 'SELECT 
                        q.id AS quote_id, 
                        q.quote, 
                        a.id AS author_id, 
                        a.author, 
                        c.id AS category_id, 
                        c.category
                      FROM ' . $this->table . ' q
                      JOIN authors a ON q.author_id = a.id
                      JOIN categories c ON q.category_id = c.id
                      WHERE q.author_id = :author_id AND q.category_id = :category_id
                      ORDER BY q.id DESC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);

            // Execute query
            $stmt->execute();
            
            return $stmt;
        }  
        
        // Create Quote
        public function create() {
            // Validate author_id exists
            $query = 'SELECT id FROM authors WHERE id = :author_id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return 'author_not_found';
            }

            // Validate category_id exists
            $query = 'SELECT id FROM categories WHERE id = :category_id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return 'category_not_found';
            }

            // Create Query
            $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id)
                    VALUES (:quote, :author_id, :category_id)';

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            return false;
        }

        // Update Quote
        public function update() {
            // Ensure required fields are provided
            if (empty($this->id) || empty($this->quote) || empty($this->author_id) || empty($this->category_id)) {
                return 'missing_parameters';
            }

            // Check if the quote exists
            $query = 'SELECT id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return 'quote_not_found';
            }

            // Validate author_id exists
            $query = 'SELECT id FROM authors WHERE id = :author_id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return 'author_not_found';
            }

            // Validate category_id exists
            $query = 'SELECT id FROM categories WHERE id = :category_id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return 'category_not_found';
            }

            // Update Query
            $query = 'UPDATE ' . $this->table . ' 
                    SET quote = :quote, author_id = :author_id, category_id = :category_id 
                    WHERE id = :id';

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            return false;
        }

        // Delete Quote
        public function delete() {
            // Ensure ID is provided
            if (empty($this->id)) {
                return 'missing_id';
            }

            // Check if the quote exists
            $query = 'SELECT id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return 'quote_not_found';
            }

            // Delete Query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            return false;
        }


    }
?>
