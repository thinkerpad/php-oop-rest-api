<?php
class Database {
    private $host;
    private $db_port; // Renamed from $port
    private $dbname;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');
        $this->dbname = getenv('DBNAME');
        $this->host = getenv('HOST');
        $this->db_port = getenv('DB_PORT'); // Renamed from PORT
    }

    public function connect() {
        if ($this->conn) {
            return $this->conn;
        }

        $dsn = "pgsql:host={$this->host};port={$this->db_port};dbname={$this->dbname};";
        try {
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            error_log('Connection Error: ' . $e->getMessage());
            throw new Exception('Unable to connect to the database: ' . $e->getMessage(), 500);
        }
    }
}
?>