<?php

$servername = "proj-mysql.uopnet.plymouth.ac.uk";
$username = "comp3000_dstephens";
$password = "ZzuY937+";
$database = "comp3000_dstephens";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve data
$sql = "SELECT * FROM recipe_table";
$result = $conn->query($sql);

// Check if there are any rows
if ($result->num_rows > 0) {
    // Fetch data and store in an array
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Output data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // Output a message as JSON if no data found
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'No data found'));
}

// Close connection
$conn->close();
?>


