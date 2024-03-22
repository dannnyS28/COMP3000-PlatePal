<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipeName = $_POST['recipeName'];
    $recipeInstructions = $_POST['recipeInstructions'];
    $calories = $_POST['calories'];
    $prepTime = $_POST['prepTime'];
    $cookTime = $_POST['cookTime'];
    $difficultyLevel = $_POST['difficultyLevel'];
    $isPublic = $_POST['isPublic'];
    $totalPrice = $_POST['totalPrice'];
    $ingredients = json_decode($_POST['ingredients'], true);
    $cloned = 0;

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

        $recipeInstructions = $conn->real_escape_string($recipeInstructions);

        $sql_recipe = "INSERT INTO recipe_table (User_ID, Recipe_Name, Recipe_Instructions, Recipe_Calories, Recipe_Prep_Time, Recipe_Cook_Time, Recipe_Difficulty_Level, Recipe_Public_Or_Private, Recipe_Price, Recipe_Cloned) VALUES ('$userId', '$recipeName', '$recipeInstructions', '$calories', '$prepTime', '$cookTime', '$difficultyLevel', '$isPublic', '$totalPrice', '$cloned')";
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

            if (isset($_FILES['recipePicture']) && $_FILES['recipePicture']['error'] == 0) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["recipePicture"]["name"]);
                if (move_uploaded_file($_FILES["recipePicture"]["tmp_name"], $target_file)) {
                    $sql_picture = "UPDATE recipe_table SET recipe_image = '$target_file' WHERE Recipe_ID = '$recipeId'";
                    $conn->query($sql_picture);
                }
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


