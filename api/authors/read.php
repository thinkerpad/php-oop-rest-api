<?php
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $author = new Author($db);

    $result = $author->read();
    $num = $result->rowCount();

    if ($num > 0) {
        $authors_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
        
            $author_item = array(
                'id' => $id,
                'author' => $author
            );
        
            array_push($authors_arr, $author_item);
        }
      
        // Turn to JSON & output
        echo json_encode($authors_arr);

    } else {
        echo json_encode(
            array('message' => 'author_id Not Found')
        );
    }
?>