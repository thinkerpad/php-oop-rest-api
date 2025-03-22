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

    // Ensure required fields are set
    if (!isset($data->id) || !isset($data->quote) || 
        !isset($data->author_id) || !isset($data->category_id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Assign values
    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Update Quote
    $response = $quote->update();

    if ($response === true) {
        // Get the ID of the newly created quote
        $quote->id = $db->lastInsertId();

        // Create an array with the quote details
        $quote_arr = array(
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author_id' => $quote->author_id,
            'category_id' => $quote->category_id
        );

        // Convert to JSON and output
        echo json_encode($quote_arr);
    } elseif ($response === 'quote_not_found') {
        echo json_encode(array('message' => 'No Quotes Found'));
    } elseif ($response === 'author_not_found') {
        echo json_encode(array('message' => 'author_id Not Found'));
    } elseif ($response === 'category_not_found') {
        echo json_encode(array('message' => 'category_id Not Found'));
    } elseif ($response === 'missing_parameters') {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    } else {
        echo json_encode(array('message' => 'Quote Not Updated'));
    }
?>
