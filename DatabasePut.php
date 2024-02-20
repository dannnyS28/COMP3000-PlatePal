<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipeName = $_POST['recipeName'];
    $ingredients = json_decode($_POST['ingredients'], true);

    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        $servername = "proj-mysql.uopnet.plymouth.ac.uk";
        $username = "comp3000_dstephens";
        $password = "ZzuY937+";
        $database = "comp3000_dstephens";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql_recipe = "INSERT INTO recipe_table (User_ID, Recipe_Name) VALUES ('$userId', '$recipeName')";
        if ($conn->query($sql_recipe) === TRUE) {
            $recipeId = $conn->insert_id;

            foreach ($ingredients as $ingredient) {
                $ingredientName = $ingredient['name'];
                $unit = $ingredient['unit'];
                $amount = isset($ingredient['amount']) ? $ingredient['amount'] : 0;
                $price = $ingredient['price'];

                $sql_ingredient = "INSERT INTO ingredients_table (Recipe_ID, User_ID, Ingredient_Name, Ingredient_Unit, Ingredient_Amount, Ingredient_Price) VALUES ('$recipeId', '$userId', '$ingredientName', '$unit', '$amount', '$price')";
                $conn->query($sql_ingredient);
            }

            echo json_encode(array('message' => 'Recipe saved successfully.'));
        } else {
            echo json_encode(array('message' => 'Error saving recipe.'));
        }

        $conn->close();
    } else {
        echo json_encode(array('message' => 'User is not logged in.'));
    }
} else {
    echo json_encode(array('message' => 'Invalid request method.'));
}
?>
