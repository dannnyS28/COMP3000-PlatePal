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
    
    $recipeQuery = "SELECT 
                        rt.Recipe_Name, 
                        rt.Recipe_Instructions, 
                        rt.Recipe_Calories, 
                        rt.Recipe_Prep_Time, 
                        rt.Recipe_Cook_Time,
                        rt.Recipe_Difficulty_Level,
                        rt.Recipe_Public_Or_Private,
                        rt.Recipe_Image,
                        GROUP_CONCAT(CONCAT(it.Ingredient_ID, ' ', it.Ingredient_Name, ' ', it.Ingredient_Unit, ' ', it.Ingredient_Amount, ' ', it.Ingredient_Price) SEPARATOR ',') AS Ingredients_Details
                    FROM 
                        recipe_table rt
                    INNER JOIN 
                        ingredients_table it ON rt.Recipe_ID = it.Recipe_ID
                    WHERE 
                        rt.Recipe_ID = ?
                    GROUP BY 
                        rt.Recipe_ID
                    UNION
                    SELECT 
                        lrt.Recipe_Name, 
                        lrt.Recipe_Instructions, 
                        lrt.Recipe_Calories, 
                        lrt.Recipe_Prep_Time, 
                        lrt.Recipe_Cook_Time,
                        lrt.Recipe_Difficulty_Level,
                        lrt.Recipe_Public_Or_Private,
                        lrt.Recipe_Image,
                        GROUP_CONCAT(CONCAT(lit.Ingredient_ID, ' ', lit.Ingredient_Name, ' ', lit.Ingredient_Unit, ' ', lit.Ingredient_Amount, ' ', lit.Ingredient_Price) SEPARATOR ',') AS Ingredients_Details
                    FROM 
                        library_recipe_table lrt
                    INNER JOIN 
                        library_ingredients_table lit ON lrt.Recipe_ID = lit.Recipe_ID
                    WHERE 
                        lrt.Recipe_ID = ?
                    GROUP BY 
                        lrt.Recipe_ID";
                    
    $recipeStatement = $conn->prepare($recipeQuery);
    $recipeStatement->bind_param("ii", $recipeID, $recipeID); 
    $recipeStatement->execute();
    
    $recipeResult = $recipeStatement->get_result();
    
    if ($recipeResult->num_rows > 0) {
        $recipeDetails = $recipeResult->fetch_assoc();
        
        if (!empty($recipeDetails['Recipe_Image'])) {
            $recipeDetails['Recipe_Image'] = '' . $recipeDetails['Recipe_Image'];
        }

        echo json_encode($recipeDetails);
    } else {
        echo json_encode(array('error' => 'Recipe not found'));
    }
} else {
    echo json_encode(array('error' => 'Recipe ID not provided'));
}

$conn->close();
?>







