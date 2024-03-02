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

        $conn->begin_transaction();

        try {
            $sql_recipe_insert = "INSERT INTO library_recipe_table (Recipe_Name, User_ID) VALUES (?, ?)";
            $stmt_recipe_insert = $conn->prepare($sql_recipe_insert);
            $stmt_recipe_insert->bind_param("si", $recipeName, $userId);
            $stmt_recipe_insert->execute();
            $recipeId = $stmt_recipe_insert->insert_id;
            $stmt_recipe_insert->close();

            foreach ($ingredients as $ingredient) {
                $ingredientName = $ingredient['name'];
                $unit = $ingredient['unit'];
                $amount = $ingredient['amount'];
                $price = $ingredient['price'];

                $sql_ingredient_insert = "INSERT INTO library_ingredients_table (Recipe_ID, Ingredient_Name, Ingredient_Unit, Ingredient_Amount, Ingredient_Price, User_ID) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_ingredient_insert = $conn->prepare($sql_ingredient_insert);
                $stmt_ingredient_insert->bind_param("isssdi", $recipeId, $ingredientName, $unit, $amount, $price, $userId);
                $stmt_ingredient_insert->execute();
                $stmt_ingredient_insert->close();
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



