<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $recipeName = $_POST['recipeName'];
    $ingredients = json_decode($_POST['ingredients'], true);

    // Retrieve user ID from session (assuming it's stored in the session)
    session_start(); // Start the session
    $userId = 1; // Assuming 'user_id' is the session variable storing the user ID

    // Validate user ID
    if (!empty($userId) && is_numeric($userId)) {
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

        // Insert recipe information into the database
        $sql_recipe = "INSERT INTO recipe_table (User_ID, Recipe_Name) VALUES ('$userId', '$recipeName')";
        if ($conn->query($sql_recipe) === TRUE) {
            $recipeId = $conn->insert_id;

            // For inserting into ingredients_table
            foreach ($ingredients as $ingredient) {
                $ingredientName = $ingredient['name'];
                $unit = $ingredient['unit'];
                $amount = isset($ingredient['amount']) ? $ingredient['amount'] : 0;
                $price = $ingredient['price'];

                $sql_ingredient = "INSERT INTO ingredients_table (Recipe_ID, Ingredient_Name, Ingredient_Unit, Ingredient_Amount, Ingredient_Price) VALUES ('$recipeId', '$ingredientName', '$unit', '$amount', '$price')";
                $conn->query($sql_ingredient);
            }

            echo json_encode(array('message' => 'Recipe saved successfully.'));
        } else {
            echo json_encode(array('message' => 'Error saving recipe.'));
        }

        // Close connection
        $conn->close();
    } else {
        echo json_encode(array('message' => 'Invalid user ID.'));
    }
} else {
    echo json_encode(array('message' => 'Invalid request method.'));
}
?>
