<?php
$servername = "proj-mysql.uopnet.plymouth.ac.uk";
$username = "comp3000_dstephens";
$password = "ZzuY937+";
$database = "comp3000_dstephens";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if recipe ID is provided
if (isset($_GET['recipeID'])) {
    // Get the recipe ID from the request
    $recipeID = $_GET['recipeID'];

    // SQL query to delete recipe based on ID
    $sql = "DELETE FROM ingredients_table WHERE Recipe_ID = $recipeID";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Success response
        http_response_code(204); // No content
    } else {
        // Error response
        http_response_code(500); // Internal server error
        echo json_encode(array('error' => $conn->error));
    }
} else {
    // Error response if recipe ID is not provided
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Recipe ID not provided'));
}

// Check if recipe ID is provided
if (isset($_GET['recipeID'])) {
    // Get the recipe ID from the request
    $recipeID = $_GET['recipeID'];

    // SQL query to delete recipe based on ID
    $sql = "DELETE FROM recipe_table WHERE Recipe_ID = $recipeID";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Success response
        http_response_code(204); // No content
    } else {
        // Error response
        http_response_code(500); // Internal server error
        echo json_encode(array('error' => $conn->error));
    }
} else {
    // Error response if recipe ID is not provided
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Recipe ID not provided'));
}

// Close connection if necessary
$conn->close();
?>
