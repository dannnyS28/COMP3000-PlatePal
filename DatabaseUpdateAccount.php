<?php
session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');
    $updated_details = json_decode($json_data, true);

    if ($updated_details === null) {
        echo json_encode(array("error" => "Error decoding JSON data"));
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
    
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $forename = $conn->real_escape_string($updated_details['Forename']);
        $surname = $conn->real_escape_string($updated_details['Surname']);
        $dob = $conn->real_escape_string($updated_details['DOB']);
        $password = $conn->real_escape_string($updated_details['Password']);
        $email = $conn->real_escape_string($updated_details['Email']);

        $sql = "UPDATE user_table SET User_Name='$forename', User_Surname='$surname', User_DOB='$dob', User_Password='$password', User_Email='$email' WHERE User_ID='$user_id'";

        if ($conn->query($sql) === TRUE) {
            echo "Account details updated successfully";
        } else {
            echo "Error updating account details: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "User is not logged in";
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed"));
}
?>





