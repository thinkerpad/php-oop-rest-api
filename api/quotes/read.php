<?php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote = new Quote($db);

// Check which filter parameters are provided
if (isset($_GET['id'])) {
    // Get single quote by ID
    include 'read_single.php';
    exit();
} elseif (isset($_GET['author_id']) && isset($_GET['category_id'])) {
    // Get quotes by author and category
    $quote->author_id = $_GET['author_id'];
    $quote->category_id = $_GET['category_id'];
    $result = $quote->read_by_author_and_category();
} elseif (isset($_GET['author_id'])) {
    // Get quotes by author
    $quote->author_id = $_GET['author_id'];
    $result = $quote->read_by_author();
} elseif (isset($_GET['category_id'])) {
    // Get quotes by category
    $quote->category_id = $_GET['category_id'];
    $result = $quote->read_by_category();
} else {
    // Get all quotes
    $result = $quote->read();
}

$num = $result->rowCount();

if ($num > 0) {
    $quotes_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $quote_item = array(
            'id' => $id, // Change 'quote_id' to 'id'
            'quote' => $quote,
            'author' => $author,
            'category' => $category
        );

        array_push($quotes_arr, $quote_item);
    }

    // Return JSON response
    echo json_encode($quotes_arr);
} else {
    echo json_encode(array('message' => 'No Quotes Found'));
}
?>
