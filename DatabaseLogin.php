<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];

    session_start();

    $servername = "proj-mysql.uopnet.plymouth.ac.uk";
    $username = "comp3000_dstephens";
    $password = "ZzuY937+";
    $database = "comp3000_dstephens";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM user_table WHERE User_Email = '$Email' AND User_Password = '$Password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $_SESSION['user_id'] = $user_data['User_ID'];
    $_SESSION['user_email'] = $user_data['User_Email'];
    
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Login successful', 'user_id' => $user_data['User_ID']));
} else {
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Invalid email or password'));
}


    $conn->close();
}
?>






