<?php 
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $author = new Author($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Check if id is missing or null
    if (!isset($data->id) || empty($data->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit; // Terminate script execution
    }

    // Set ID to DELETE
    $author->id = $data->id;

    // Attempt to delete author
    $result = $author->delete();

    if ($result === "not_found") {
        echo json_encode(array('message' => 'author_id not found'));
    } elseif ($result === true) {
        echo json_encode(array('message' => 'Author deleted'));
    } else {
        echo json_encode(array('message' => 'Author not deleted'));
    }
?>
