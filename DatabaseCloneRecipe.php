<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipeId = $_POST['recipeID'];
    $cloned = 1;  
    $public = 0;

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

        $conn->begin_transaction();

        try {
            $sql_select_recipe = "SELECT Recipe_Name, Recipe_Instructions, Recipe_Calories, Recipe_Prep_Time, Recipe_Cook_Time, Recipe_Difficulty_Level, Recipe_Price, recipe_image FROM recipe_table WHERE Recipe_ID = ?";
            $stmt_select_recipe = $conn->prepare($sql_select_recipe);
            $stmt_select_recipe->bind_param("i", $recipeId);
            $stmt_select_recipe->execute();
            $stmt_select_recipe->bind_result($recipeName, $recipeInstructions, $recipeCalories, $recipePrepTime, $recipeCookTime, $recipeDifficultyLevel, $recipePrice, $recipeImage);
            if (!$stmt_select_recipe->fetch()) {
                throw new Exception('Original recipe not found.');
            }
            $stmt_select_recipe->close();

            $sql_recipe_insert = "INSERT INTO library_recipe_table (Recipe_Name, User_ID, Recipe_Instructions, Recipe_Calories, Recipe_Prep_Time, Recipe_Cook_Time, Recipe_Difficulty_Level, Recipe_Price, Recipe_Public_Or_Private, Recipe_Cloned, Recipe_Image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_recipe_insert = $conn->prepare($sql_recipe_insert);
            $stmt_recipe_insert->bind_param("sisddiidiis", $recipeName, $userId, $recipeInstructions, $recipeCalories, $recipePrepTime, $recipeCook_Time, $recipeDifficultyLevel, $recipePrice, $public, $cloned, $recipeImage);
            $stmt_recipe_insert->execute();
            $newRecipeId = $stmt_recipe_insert->insert_id;
            $stmt_recipe_insert->close();

            $sql_select_ingredients = "SELECT Ingredient_Name, Ingredient_Unit, Ingredient_Amount, Ingredient_Price FROM ingredients_table WHERE Recipe_ID = ?";
            $stmt_select_ingredients = $conn->prepare($sql_select_ingredients);
            $stmt_select_ingredients->bind_param("i", $recipeId);
            $stmt_select_ingredients->execute();
            $result = $stmt_select_ingredients->get_result();

            $sql_ingredient_insert = "INSERT INTO library_ingredients_table (Recipe_ID, Ingredient_Name, Ingredient_Unit, Ingredient_Amount, Ingredient_Price, User_ID) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_ingredient_insert = $conn->prepare($sql_ingredient_insert);
            while ($row = $result->fetch_assoc()) {
                $stmt_ingredient_insert->bind_param("isssdi", $newRecipeId, $row['Ingredient_Name'], $row['Ingredient_Unit'], $row['Ingredient_Amount'], $row['Ingredient_Price'], $userId);
                $stmt_ingredient_insert->execute();
            }
            $stmt_ingredient_insert->close();

            $conn->commit();
            echo json_encode(array('message' => 'Recipe copied successfully.', 'newImagePath' => $recipeImage));
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(array('message' => 'Error copying recipe: ' . $e->getMessage()));
        }

        $conn->close();
    } else {
        echo json_encode(array('message' => 'User is not logged in.'));
    }
} else {
    echo json_encode(array('message' => 'Invalid request method.'));
}
?>



                








