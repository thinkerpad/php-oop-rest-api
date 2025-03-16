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

    // Check if input is empty or if id or category is missing
    if (!isset($data->id) || !isset($data->category) || empty(trim($data->category))) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit(); // Terminate script execution
    }

    // Set ID to UPDATE
    $category->id = $data->id;
    $category->category = $data->category;

    // Attempt to update category
    $result = $category->update();

    if ($result === "not_found") {
        echo json_encode(array('message' => 'category_id not found'));
    } elseif ($result === true) {
        echo json_encode(array('message' => 'Category Updated'));
    } else {
        echo json_encode(array('message' => 'Category not updated'));
    }
?>
