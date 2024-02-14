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

    $sql = "SELECT * FROM user_table";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        if (in_array($Email, $array)) {
            echo 'this array contains Email';
            if (in_array($Password, $array)) {
                echo 'this array contains Password';
            }
            else{
                echo 'this array doesnt contain password';
            }
        }
        
        else{
            echo 'this array doesnt contain email';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    } 
    else {
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'No data found'));
    }

    $conn->close();
}
?>




