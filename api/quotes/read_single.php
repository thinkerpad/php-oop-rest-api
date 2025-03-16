<?php
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote = new Quote($db);

    // Check if 'id' is set in the GET request, otherwise return an error message
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo json_encode(array('message' => 'quote_id Not Found'));
        exit();
    }

    $quote->id = $_GET['id'];

    // Fetch the single quote record
    $quote->read_single();

    // Check if the quote exists (by checking if quote attribute is null)
    if ($quote->quote === null) {
        echo json_encode(array('message' => 'quote_id Not Found'));
        exit();
    }

    // Create an array with the quote details
    $quote_arr = array(
        'quote_id' => $quote->id,
        'quote' => $quote->quote,
        'author_id' => $quote->author_id,
        'category_id' => $quote->category_id
    );

    // Convert to JSON and output
    echo json_encode($quote_arr);
?>
