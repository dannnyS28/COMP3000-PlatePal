<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipeID = $_POST['recipeID'];
    $recipeName = $_POST['recipeName'];
    $recipeInstructions = $_POST['recipeInstructions'];
    $recipeCalories = $_POST['recipeCalories'];
    $recipePrep = $_POST['recipePrep'];
    $recipeCook = $_POST['recipeCook'];
    $recipeDifficulty = $_POST['recipeDifficulty'];
    $isPublic = $_POST['isPublic'];
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

        $conn->begin_transaction();

        try {
            if ($recipeID) {
                $sql_recipe_update = "UPDATE recipe_table SET Recipe_Name=?, Recipe_Instructions=?, Recipe_Calories=?, Recipe_Prep_Time=?, Recipe_Cook_Time=?, Recipe_Difficulty_Level=?, Recipe_Public_Or_Private=? WHERE Recipe_ID=?";
                $stmt_recipe_update = $conn->prepare($sql_recipe_update);
                $isPublicValue = intval($isPublic);
                $stmt_recipe_update->bind_param('ssssiiii', $recipeName, $recipeInstructions, $recipeCalories, $recipePrep, $recipeCook, $recipeDifficulty, $isPublicValue, $recipeID);
                $stmt_recipe_update->execute();
                $stmt_recipe_update->close();
            } else {
                echo json_encode(array('message' => 'Recipe ID is required.'));
                exit;
            }

            foreach ($ingredients as $ingredient) {
                $ingredientId = $ingredient['id']; 
                $ingredientName = $ingredient['name'];
                $unit = $ingredient['unit'];
                $amount = isset($ingredient['amount']) ? $ingredient['amount'] : 0;
                $price = $ingredient['price'];

                if ($ingredientId) {
                    $sql_ingredient_update = "UPDATE ingredients_table SET Ingredient_Name=?, Ingredient_Unit=?, Ingredient_Amount=?, Ingredient_Price=? WHERE Ingredient_ID=?";
                    $stmt_ingredient_update = $conn->prepare($sql_ingredient_update);
                    $stmt_ingredient_update->bind_param("ssdsi", $ingredientName, $unit, $amount, $price, $ingredientId);
                    $stmt_ingredient_update->execute();
                    $stmt_ingredient_update->close();
                } else {
                    $sql_ingredient_insert = "INSERT INTO ingredients_table (Ingredient_Name, Ingredient_Unit, Ingredient_Amount, Ingredient_Price, Recipe_ID, User_ID) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt_ingredient_insert = $conn->prepare($sql_ingredient_insert);
                    $stmt_ingredient_insert->bind_param("ssdsii", $ingredientName, $unit, $amount, $price, $recipeID, $userId);
                    $stmt_ingredient_insert->execute();
                    $stmt_ingredient_insert->close();
                }
            }

            $conn->commit();
            echo json_encode(array('message' => 'Recipe updated successfully.'));
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(array('message' => 'Error updating recipe: ' . $e->getMessage()));
        }

        $conn->close();
    } else {
        echo json_encode(array('message' => 'User is not logged in.'));
    }
} else {
    echo json_encode(array('message' => 'Invalid request method.'));
}
?>






















