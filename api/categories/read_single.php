<?php
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate category object
    $category = new Category($db);

    // Check if 'id' is set in the GET request, otherwise return an error message
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo json_encode(array('message' => 'category_id Not Found'));
        exit();
    }

    $category->id = $_GET['id'];

    // Fetch the single category record
    $category->read_single();

    // Check if the category exists through their category attribute
    // The category table specifies that category can't be null
    if ($category->category === null) {
        echo json_encode(array('message' => 'category_id Not Found'));
        exit();
    }

    // Create an array with the category details
    $category_arr = array(
        'id' => $category->id,
        'category' => $category->category
    );

    // Convert to JSON and output
    echo json_encode($category_arr);
?>