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

    // Check if input is empty or author is missing
    if (!isset($data->author) || empty(trim($data->author))) {
        echo "Missing Required Parameters";
        exit; // Terminate the script
    }

    // Assign author data
    $author->author = $data->author;

    // Create Author
    if ($author->create()) {
        // Get the ID of the newly created author
        $author->id = $db->lastInsertId();

        // Create an array with the author details
        $author_arr = array(
            'id' => $author->id,
            'author' => $author->author,
        );

        // Convert to JSON and output
        echo json_encode($author_arr);
    } else {
        echo json_encode(
            array('message' => 'Author Not Created')
        );
    }
?>