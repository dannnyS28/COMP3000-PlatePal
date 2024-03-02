<?php
$servername = "proj-mysql.uopnet.plymouth.ac.uk";
$username = "comp3000_dstephens";
$password = "ZzuY937+";
$database = "comp3000_dstephens";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['recipeID'])) {
    $recipeID = $_GET['recipeID'];
    $sql = "DELETE FROM ingredients_table WHERE Recipe_ID = $recipeID";
    if ($conn->query($sql) === TRUE) {
        http_response_code(204); 
    } else {
        http_response_code(500);
        echo json_encode(array('error' => $conn->error));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Recipe ID not provided'));
}

if (isset($_GET['recipeID'])) {
    $recipeID = $_GET['recipeID'];
    $sql = "DELETE FROM recipe_table WHERE Recipe_ID = $recipeID";
    if ($conn->query($sql) === TRUE) {
        http_response_code(204);
    } else {
        http_response_code(500);
        echo json_encode(array('error' => $conn->error));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Recipe ID not provided'));
}

if (isset($_GET['recipeID'])) {
    $recipeID = $_GET['recipeID'];
    $sql = "DELETE FROM library_ingredients_table WHERE Recipe_ID = $recipeID";
    if ($conn->query($sql) === TRUE) {
        http_response_code(204); 
    } else {
        http_response_code(500);
        echo json_encode(array('error' => $conn->error));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Recipe ID not provided'));
}

if (isset($_GET['recipeID'])) {
    $recipeID = $_GET['recipeID'];
    $sql = "DELETE FROM library_recipe_table WHERE Recipe_ID = $recipeID";
    if ($conn->query($sql) === TRUE) {
        http_response_code(204);
    } else {
        http_response_code(500);
        echo json_encode(array('error' => $conn->error));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Recipe ID not provided'));
}

$conn->close();
?>

