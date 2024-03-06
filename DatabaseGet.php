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

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $data = array();

    $sql_recipe = "SELECT * FROM recipe_table WHERE user_id = $user_id";
    $result_recipe = $conn->query($sql_recipe);

    if ($result_recipe->num_rows > 0) {
        while ($row = $result_recipe->fetch_assoc()) {
            $data[] = $row;
        }
    }

    $sql_library = "SELECT * FROM library_recipe_table WHERE user_id = $user_id";
    $result_library = $conn->query($sql_library);

    if ($result_library->num_rows > 0) {
        while ($row = $result_library->fetch_assoc()) {
            $data[] = $row;
        }
    }

    if (!empty($data)) {
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'No recipes found for this user'));
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'User not logged in'));
}

$conn->close();
?>






