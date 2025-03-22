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

  // Check if input is empty or if id or author is missing
  if (!isset($data->id) || !isset($data->author) || empty(trim($data->author))) {
      echo json_encode(array('message' => 'Missing Required Parameters'));
      exit();
  }

  // Set ID and author to update
  $author->id = $data->id;
  $author->author = $data->author;

  // Attempt to update author
  $result = $author->update();

  if ($result === "not_found") {
      echo json_encode(array('message' => 'Author ID not found'));
  } elseif ($result === true) {
      // Create an array with the author details (use the existing $author->id)
        $author_arr = array(
            'id' => $author->id,
            'author' => $author->author,
        );

        // Convert to JSON and output
        echo json_encode($author_arr);
  } else {
      echo json_encode(array('message' => 'Author not updated'));
  }
?>
