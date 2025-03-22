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
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit; // Terminate the script
    }

    // Assign category data
    $category->category = $data->category;

    // Create Category
    if ($category->create()) {
        // Get the ID of the newly created category
        $category->id = $db->lastInsertId();

        // Create an array with the category details
        $category_arr = array(
            'id' => $category->id,
            'category' => $category->category,
        );

        // Convert to JSON and output
        echo json_encode($category_arr);
    } else {
        echo json_encode(
            array('message' => 'Category Not Created')
        );
    }
?>