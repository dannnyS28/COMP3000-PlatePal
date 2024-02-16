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

    $sql = "SELECT * FROM user_table WHERE user_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc(); 
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'No user found'));
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'User not logged in'));
}

$conn->close();
?>
