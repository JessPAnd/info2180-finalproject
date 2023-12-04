<?php

$host = 'localhost:3307';
$username = 'Peter';
$password = 'password115';
$dname = 'dolphin_crm';

$conn = new PDO("mysql:host=$host;dbname=$dname;charset=utf8mb4", $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_id']) && isset($_POST['note_text'])) {
    $contact_id = filter_input(INPUT_POST, 'contact_id', FILTER_SANITIZE_STRING);
    $note_text = filter_input(INPUT_POST, 'note_text', FILTER_SANITIZE_STRING);

        // Assign the current contact to the current user
        // Assuming you have a session variable for the current user ID, adjust as needed
        // $current_user_id = $_SESSION['user_id']; 
       try{
         $current_user_id = 3; 

        // Update the 'assigned_to' field in the 'contacts' table
        $stmt = $conn->prepare("INSERT INTO notes (contact_id, comment, created_by) VALUES (:contact_id, :comment, :current_user_id)");
        $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
        $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $note_text, PDO::PARAM_STR);
        $stmt->execute();

        echo 'Note has been added to contact.';
       } catch (PDOException $e) {
             echo 'Error: ' . $e->getMessage();
        }
    }
?>
