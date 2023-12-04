<?php

// include 'update-contact-type.php';

$host = 'localhost:3307';
$username = 'Peter';
$password = 'password115';
$dname = 'dolphin_crm';

$conn = new PDO("mysql:host=$host;dbname=$dname;charset=utf8mb4", $username, $password);

$stmt = $conn->query("SELECT * FROM contacts");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$result = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact_id = filter_input(INPUT_POST, 'contact_id', FILTER_SANITIZE_STRING);
    $note_text = filter_input(INPUT_POST, 'note_text',  FILTER_SANITIZE_STRING);
    
    // For some reason I was having issues retrieving content from $_POST
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true); // true for associative array
    // // Now $data should contain the decoded JSON data
    $contact_id = $data['contact_id'];
    $note_text = $data['note_text'];
       
        // $current_user_id = $_SESSION['user_id']; 
        if (!empty($contact_id)) {
            $current_user_id = 3; 

            $stmt3 = $conn->prepare("INSERT INTO notes (contact_id, comment, created_by) VALUES (:contact_id, :comment, :current_user_id)");
            $stmt3->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
            $stmt3->bindParam(':contact_id', $contact_id, PDO::PARAM_STR);
            $stmt3->bindParam(':comment', $note_text, PDO::PARAM_STR);
            $stmt3->execute();

        } else {
            echo 'Invalid contact_id';
        }
      
    }elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['contact_id'])) {
    $contact_id = filter_input(INPUT_GET, 'contact_id', FILTER_SANITIZE_STRING);
    $stmt = $conn->prepare('SELECT contacts.*,
                                users.id AS user_id, 
                                users.firstname AS user_firstname, 
                                users.lastname AS user_lastname,
                                users.password AS user_password,
                                users.email AS user_email,
                                users.role AS user_role,
                                users.created_at AS user_created_at                    
                                FROM contacts 
                                JOIN users ON contacts.created_by = users.id
                                WHERE contacts.id =  :contact_id' );
    $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $conn->prepare('SELECT 
                                notes.contact_id AS notes_contact_id,
                                notes.comment AS notes_comment,
                                notes.created_by AS notes_created_by,
                                notes.created_at AS notes_created_at,
                                users.id AS user_id, 
                                users.firstname AS user_firstname, 
                                users.lastname AS user_lastname,
                                contacts.id
                                FROM notes 
                                JOIN users ON notes.created_by = users.id
                                JOIN contacts ON contacts.id = notes.contact_id
                                WHERE contacts.id =  :contact_id' );
    $stmt2->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
    $stmt2->execute();
    $notesresult = $stmt2->fetchAll(PDO::FETCH_ASSOC);
} 
    

?>


<!-- Displaying Results -->
<div>
    <?php if (!empty($result)): ?>
        <div>
            <h2><?= $result[0]['firstname'] ?> <?= $result[0]['lastname'] ?></h2>
            <p>Created on: <?= $result[0]['created_at'] ?> by <?= $result[0]['user_firstname'] ?> <?= $result[0]['user_lastname'] ?></p>
            <p>Updated on: <?= $result[0]['updated_at'] ?></p>
            <button onclick="updateContactType('assignToMe', <?= $result[0]['id'] ?>)">Assign to me</button>
            <button onclick="updateContactType('switchToSalesLead', <?= $result[0]['id'] ?>)">Switch to Sales Lead</button>
        </div>

        <div>
            <p>Email</p>
            <p><?= $result[0]['email'] ?></p>
            <p>Telephone</p>
            <p><?= $result[0]['telephone'] ?></p>
            <p>Compnay</p>
            <p><?= $result[0]['company'] ?></p>
            <p>Assigned To</p>
            <p><?= $result[0]['user_firstname'] ?> <?= $result[0]['user_lastname'] ?></p>
        </div>

        <div>
            <h3>Notes</h3>
            <?php foreach ($notesresult as $record): ?>
            
                    <h4> <?= $record['user_firstname'] ?> <?= $record['user_lastname'] ?> </h4>
                    <p> <?= $record['notes_comment'] ?> </p>
                    <p> <?= $record['notes_created_at'] ?> </p>
                
            <?php endforeach; ?>
            <div>
                <h3>Add a note about Michael</h3>
                <textarea name="notepad" id="notepad" cols="30" rows="10"></textarea>
                <button id="notesbutton">Submit Note</button>
            </div> 
        </div>
           
        <?php else: ?>
            <p>No contact found.</p>
        <?php endif; ?>


        <ul>
            <?php foreach ($result[0] as $key => $value): ?>
                <li><?= $key ?>: <?= $value ?></li>
            <?php endforeach; ?>
        </ul>


     
</div>

