<?php

session_start();
$host = 'localhost:3307';
$username = 'DolphinAdmin';
$password = 'password123';
$dname = 'dolphin_crm';

$conn = new PDO("mysql:host=$host;dbname=$dname;charset=utf8mb4", $username, $password);

$stmt = $conn->query("SELECT * FROM contacts");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$result = '';

//POST Request for notes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    // For some reason I was having issues retrieving content from $_POST
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true); // true for associative array
   
    // Escaping & Sanitizing of Data
    $contact_id = filter_var($data['contact_id'], FILTER_SANITIZE_STRING);
    $escaped_note_text = htmlspecialchars($data['note_text'], ENT_QUOTES, 'UTF-8');
    $note_text = filter_var($escaped_note_text, FILTER_SANITIZE_STRING);

       
        if (!empty($contact_id)) {
            $current_user_id = 3; 

            $stmt3 = $conn->prepare("INSERT INTO notes (contact_id, comment, created_by) VALUES (:contact_id, :comment, :current_user_id)");
            $stmt3->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
            $stmt3->bindParam(':contact_id', $contact_id, PDO::PARAM_STR);
            $stmt3->bindParam(':comment', $note_text, PDO::PARAM_STR);
            $stmt3->execute();
            echo 'post query executed';
        } else {
            echo 'Invalid contact_id';
        }
      
    }

    // GET Request for Assign User & Switching Type
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['contact_id']) && isset($_GET['action'])) {
        $contact_id = filter_input(INPUT_GET, 'contact_id', FILTER_SANITIZE_STRING);
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    
         // Assign the current contact to the current user
        if ($action === 'assignToMe') {
           
            //Session variable for the current user ID
            $current_user_id = $_SESSION['user_id']; 

            // Update the 'assigned_to' field in the 'contacts' table
            $stmt = $conn->prepare("UPDATE contacts SET assigned_to = :current_user_id, updated_at = NOW() WHERE id = :contact_id");
            $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
            $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
            $stmt->execute();
    
            echo 'Contact assigned to the current user.';
        } elseif ($action === 'switchType') {
            
            // Check the current type
            $stmt = $conn->prepare("SELECT contacts.type FROM contacts WHERE id = :contact_id");
            $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
            $stmt->execute();
            $switchresult = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($switchresult && $switchresult['type'] === 'Support') {
                // Update the 'type' field in the 'contacts' table
                $stmt = $conn->prepare("UPDATE contacts SET type = 'Sales Lead', updated_at = NOW() WHERE id = :contact_id");
                $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
                $stmt->execute();

                echo 'Contact type switched to Sales Lead.';
            } elseif ($switchresult && $switchresult['type'] === 'Sales Lead') {
                // If the current type is 'Sales Lead', switch it to 'Support'
                $stmt = $conn->prepare("UPDATE contacts SET type = 'Support', updated_at = NOW() WHERE id = :contact_id");
                $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
                $stmt->execute();

                echo 'Contact type switched to Support.';
            } else {
                echo 'Invalid contact data or contact type.';
            }
            }
    } 
//GET Request to load page
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['contact_id'])) {
    $contact_id = filter_input(INPUT_GET, 'contact_id', FILTER_SANITIZE_STRING);
    $stmt = $conn->prepare('SELECT contacts.*,
                                users.id AS user_id, 
                                users.firstname AS user_firstname, 
                                users.lastname AS user_lastname,
                                users.password AS user_password,
                                users.email AS user_email,
                                users.role AS user_role,
                                users.created_at AS user_created_at,
                                DATE_FORMAT(contacts.created_at, "%M %e %Y") AS formatted_created_at,
                                DATE_FORMAT(contacts.updated_at, "%M %e %Y") AS formatted_updated_at
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
                                contacts.id,
                                DATE_FORMAT(notes.created_at, "%M %e %Y") AS formatted_notes_created_at
                                FROM notes 
                                JOIN users ON notes.created_by = users.id
                                JOIN contacts ON contacts.id = notes.contact_id
                                WHERE contacts.id =  :contact_id' );
    $stmt2->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
    $stmt2->execute();
    $notesresult = $stmt2->fetchAll(PDO::FETCH_ASSOC);
} 

?>



<div>
    <?php if (!empty($result)): ?>
        <div class="profile-top">
            <section class="profile-name">
            <div>
            <img src="./avatar-1577909_1280.png" alt="user avatar">
            </div>
            <div>
            <h2><?= $result[0]['firstname'] ?> <?= $result[0]['lastname'] ?></h2>
            <p>Created on: <?= $result[0]['formatted_created_at'] ?> by <?= $result[0]['user_firstname'] ?> <?= $result[0]['user_lastname'] ?></p>
            <p>Updated on: <?= $result[0]['formatted_updated_at'] ?></p>
            </div>
            </section>
            <section class="profile-btns">
            <button class="btn btn-assign"id="assign" current-contact-id="<?= $result[0]['id'] ?>">Assign to me</button>
            <!-- <button onclick="updateContactType('switchToSalesLead', <?= $result[0]['id'] ?>)">Switch to Sales Lead</button> -->
           <button class="btn btn-switch" id="switch" current-contact-id="<?= $result[0]['id'] ?>">
                       <?= ($result[0]['type'] === 'Sales Lead') ? 'Switch to Support' : 'Switch to Sales Lead' ?>
                </button>
            </section>
        </div>

        <div class="profile-info">
            <section>
            <p>Email</p>
            <p><?= $result[0]['email'] ?></p>
            </section>
            <section>
            <p>Telephone</p>
            <p><?= $result[0]['telephone'] ?></p>
            </section>
            <section>
            <p>Compnay</p>
            <p><?= $result[0]['company'] ?></p>
            </section>
            <section>
            <p>Assigned To</p>
            <p><?= $result[0]['user_firstname'] ?> <?= $result[0]['user_lastname'] ?></p>
            </section>
        </div>

        <div class="profile-notes">
            <h3>Notes</h3>
            <hr>
            <?php foreach ($notesresult as $record): ?>
            
                    <div class="prev-notes">
                    <h4> <?= $record['user_firstname'] ?> <?= $record['user_lastname'] ?> </h4>
                    <p> <?= $record['notes_comment'] ?> </p>
                    <p> <?= $record['formatted_notes_created_at'] ?> </p>
                    </div>
                
            <?php endforeach; ?>
            <div class="create-note">
                <h3>Add a note about <?= $result[0]['firstname'] ?></h3>
                <textarea name="notepad" id="notepad" cols="30" rows="10"></textarea>
                <button class="create-note-btn" id="notesbutton">Submit Note</button>
            </div> 
        </div>
           
        <?php else: ?>
            <p>No contact found.</p>
        <?php endif; ?>
</div>

