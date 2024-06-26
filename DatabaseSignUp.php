<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Forename = $_POST['Forename'];
    $Surname = $_POST['Surname'];
    $DOB = $_POST['DOB'];
    $Password = $_POST['Password'];
    $Email = $_POST['Email'];

    if (empty($Forename) || empty($Surname) || empty($DOB) || empty($Password) || empty($Email)) {
        echo json_encode(array('message' => 'All fields are required.'));
        exit;
    }
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array('message' => 'Invalid email format.'));
        exit;
    }

    $servername = "proj-mysql.uopnet.plymouth.ac.uk";
    $username = "comp3000_dstephens";        
    $password = "ZzuY937+";
    $database = "comp3000_dstephens";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql_signup = "INSERT INTO user_table (User_Name, User_Surname, User_DOB, User_Password, User_Email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_signup);
    $stmt->bind_param("sssss", $Forename, $Surname, $DOB, $Password, $Email);

    if ($stmt->execute()) {
        echo json_encode(array('message' => 'success'));
    } else {
        echo json_encode(array('message' => 'failed' . $conn->error));
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array('message' => 'Invalid request.'));
}
?>

