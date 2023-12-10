<?php
session_start();

$conn = mysqli_connect('localhost', 'Michael', 'password123', 'dolphin_crm');
if ($conn->connect_error) {
    die('Connection Error: ' . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log(print_r($data, true)); 
    $title = isset($data['title']) ? $data['title'] : '';
    $fname = isset($data['fname']) ? $data['fname'] : '';
    $lname = isset($data['lname']) ? $data['lname'] : '';
    $email = isset($data['email']) ? $data['email'] : '';
    $phone = isset($data['phone']) ? $data['phone'] : '';
    $company = isset($data['company']) ? $data['company'] : '';
    $type = isset($data['type']) ? $data['type'] : '';
    $assignedto = isset($data['assignedto']) ? $data['assignedto'] : '';


    $sql = 'INSERT INTO contacts (title, firstname, lastname, email, telephone, company, type, assigned_to) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss', $title, $fname, $lname, $email, $phone, $company, $type, $assignedto);

   
    if ($dataStoredSuccessfully) {
        echo 'Data has been stored successfully!';
    } else {
        echo 'Error: Failed to store data.';
    }


    if ($stmt->execute()) {
        echo 'Data has been stored successfully!';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
}
?>