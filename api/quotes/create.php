<?php
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure required fields are provided
    if (!isset($data->quote) || empty(trim($data->quote)) || 
        !isset($data->author_id) || !isset($data->category_id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Assign values
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Create Quote
    $response = $quote->create();

    if ($response === true) {
        echo json_encode(array('message' => 'Quote Created'));
    } elseif ($response === 'author_not_found') {
        echo json_encode(array('message' => 'author_id Not Found'));
    } elseif ($response === 'category_not_found') {
        echo json_encode(array('message' => 'category_id Not Found'));
    } else {
        echo json_encode(array('message' => 'Quote Not Created'));
    }
?>
