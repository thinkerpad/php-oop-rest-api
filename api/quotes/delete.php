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

    // Ensure required ID is provided
    if (!isset($data->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Assign ID
    $quote->id = $data->id;

    // Delete Quote
    $response = $quote->delete();

    if ($response === true) {
        echo json_encode(array('id' => $quote->id));
    } elseif ($response === 'quote_not_found') {
        echo json_encode(array('message' => 'No Quotes Found'));
    } elseif ($response === 'missing_id') {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    } else {
        echo json_encode(array('message' => 'Quote Not Deleted'));
    }
?>
