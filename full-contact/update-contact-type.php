<?php

$host = 'localhost:3307';
$username = 'Peter';
$password = 'password115';
$dname = 'dolphin_crm';

$conn = new PDO("mysql:host=$host;dbname=$dname;charset=utf8mb4", $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['contact_id']) && isset($_GET['action'])) {
    $contact_id = filter_input(INPUT_GET, 'contact_id', FILTER_SANITIZE_STRING);
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);


    if ($action === 'assignToMe') {
        // Assign the current contact to the current user
        // Assuming you have a session variable for the current user ID, adjust as needed
        $current_user_id = $_SESSION['user_id']; // Adjust the session variable based on your implementation

        // Update the 'assigned_to' field in the 'contacts' table
        $stmt = $conn->prepare("UPDATE contacts SET assigned_to = :current_user_id, updated_at = NOW() WHERE id = :contact_id");
        $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
        $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
        $stmt->execute();

        echo 'Contact assigned to the current user.';
    } elseif ($action === 'switchToSalesLead') {
        // Switch the contact type to 'sales lead' if it is currently 'support'
        // Assuming you have a 'type' column in the 'contacts' table, adjust as needed

        // Check the current type
        $stmt = $conn->prepare("SELECT contacts.type FROM contacts WHERE id = :contact_id");
        $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['type'] === 'Support') {
            // Update the 'type' field in the 'contacts' table
            $stmt = $conn->prepare("UPDATE contacts SET contacts.type = 'Sales Lead', updated_at = NOW() WHERE id = :contact_id");
            $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
            $stmt->execute();

            echo 'Contact type switched to Sales Lead.';
        } else {
            echo 'Contact type is not currently Support.';
        }
    } else {
        echo 'Invalid action.';
    }
} else {
    echo 'Invalid request.';
}
?>
