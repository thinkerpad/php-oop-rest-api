<?php
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $author = new Author($db);

    // Check if 'id' is set in the GET request, otherwise return an error message
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo json_encode(array('message' => 'author_id Not Found'));
        exit();
    }

    $author->id = $_GET['id'];

    // Fetch the single author record
    $author->read_single();

    // Check if the author exists through their author attribute
    // The author table specifies that author can't be null
    if ($author->author === null) {
        echo json_encode(array('message' => 'author_id Not Found'));
        exit();
    }

    // Create an array with the author details
    $author_arr = array(
        'id' => $author->id,
        'author' => $author->author
    );

    // Convert to JSON and output
    echo json_encode($author_arr);
?>
