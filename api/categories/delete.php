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

    // Check if id is missing or null
    if (!isset($data->id) || empty($data->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit; // Terminate script execution
    }

    // Set ID to DELETE
    $category->id = $data->id;

    // Attempt to delete category
    $result = $category->delete();

    if ($result === "not_found") {
        echo json_encode(array('message' => 'category_id not found'));
    } elseif ($result === true) {
        echo json_encode(array('message' => 'Category deleted'));
    } else {
        echo json_encode(array('message' => 'Category not deleted'));
    }
?>