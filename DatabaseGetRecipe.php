<?php
session_start();

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
    
    $recipeQuery = "SELECT recipe_table.Recipe_Name, GROUP_CONCAT(CONCAT(ingredients_table.Ingredient_ID, ' ', ingredients_table.Ingredient_Name, ' ', ingredients_table.Ingredient_Unit, ' ', ingredients_table.Ingredient_Amount, ' ', ingredients_table.Ingredient_Price) SEPARATOR ',') AS Ingredients_Details
                    FROM recipe_table
                    INNER JOIN ingredients_table ON recipe_table.Recipe_ID = ingredients_table.Recipe_ID
                    WHERE recipe_table.Recipe_ID = ?
                    GROUP BY recipe_table.Recipe_ID";
    $recipeStatement = $conn->prepare($recipeQuery);
    $recipeStatement->bind_param("i", $recipeID); 
    $recipeStatement->execute();
    
    $recipeResult = $recipeStatement->get_result();
    
    if ($recipeResult->num_rows > 0) {
        $recipeDetails = $recipeResult->fetch_assoc();
        
        echo json_encode($recipeDetails);
    } else {
        echo json_encode(array('error' => 'Recipe not found'));
    }
} else {
    echo json_encode(array('error' => 'Recipe ID not provided'));
}

$conn->close();
?>




