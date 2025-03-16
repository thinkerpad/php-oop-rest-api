<?php 
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate category object
    $category = new Category($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Check if input is empty or category is missing
    if (!isset($data->category) || empty(trim($data->category))) {
        echo "Missing Required Parameters";
        exit; // Terminate the script
    }

    // Assign category data
    $category->category = $data->category;

    // Create Category
    if ($category->create()) {
        echo json_encode(
            array('message' => 'Category Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Category Not Created')
        );
    }
?>