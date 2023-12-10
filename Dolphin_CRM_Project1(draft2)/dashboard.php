<?php
session_start();
$host = 'localhost:3307';
$username = 'Peter';
$password = 'password115';
$dname = 'dolphin_crm';

$conn = new PDO("mysql:host=$host;dbname=$dname;charset=utf8mb4", $username, $password);

$stmt = $conn->query("SELECT * FROM contacts");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($results);
$result = '';
//POST Request for notes
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
//     // For some reason I was having issues retrieving content from $_POST
//     $jsonData = file_get_contents("php://input");
//     $data = json_decode($jsonData, true); // true for associative array
   
//     // Escaping & Sanitizing of Data
//     $contact_id = filter_var($data['contact_id'], FILTER_SANITIZE_STRING);
//     $escaped_note_text = htmlspecialchars($data['note_text'], ENT_QUOTES, 'UTF-8');
//     $note_text = filter_var($escaped_note_text, FILTER_SANITIZE_STRING);

       
//         if (!empty($contact_id)) {
//             $current_user_id = 3; 

//             $stmt3 = $conn->prepare("INSERT INTO notes (contact_id, comment, created_by) VALUES (:contact_id, :comment, :current_user_id)");
//             $stmt3->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
//             $stmt3->bindParam(':contact_id', $contact_id, PDO::PARAM_STR);
//             $stmt3->bindParam(':comment', $note_text, PDO::PARAM_STR);
//             $stmt3->execute();
//             echo 'post query executed';
//         } else {
//             echo 'Invalid contact_id';
//         }
      
//     }

    // GET Request for Assign User & Switching Type
// if ($_SERVER['REQUEST_METHOD'] && isset($_GET['filter'])) {
       
//         $filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING);
    
//          // Assign the current contact to the current user
//         if ($filter === 'assignToMe') {
           
//             //Session variable for the current user ID
//             $current_user_id = $_SESSION['user_id']; 

//             // Update the 'assigned_to' field in the 'contacts' table
//             $stmt = $conn->prepare("UPDATE contacts SET assigned_to = :current_user_id, updated_at = NOW() WHERE id = :contact_id");
//             $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
//             $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
//             $stmt->execute();
    
//             echo 'Contact assigned to the current user.';
//         } elseif ($action === 'switchType') {
            
//             // Check the current type
//             $stmt = $conn->prepare("SELECT contacts.type FROM contacts WHERE id = :contact_id");
//             $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
//             $stmt->execute();
//             $switchresult = $stmt->fetch(PDO::FETCH_ASSOC);

//             if ($switchresult && $switchresult['type'] === 'Support') {
//                 // Update the 'type' field in the 'contacts' table
//                 $stmt = $conn->prepare("UPDATE contacts SET type = 'Sales Lead', updated_at = NOW() WHERE id = :contact_id");
//                 $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
//                 $stmt->execute();

//                 echo 'Contact type switched to Sales Lead.';
//             } elseif ($switchresult && $switchresult['type'] === 'Sales Lead') {
//                 // If the current type is 'Sales Lead', switch it to 'Support'
//                 $stmt = $conn->prepare("UPDATE contacts SET type = 'Support', updated_at = NOW() WHERE id = :contact_id");
//                 $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
//                 $stmt->execute();

//                 echo 'Contact type switched to Support.';
//             } else {
//                 echo 'Invalid contact data or contact type.';
//             }
//             }
//     } 



//GET Request to load page
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filter'])) {
       
    // $filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING);

     // Assign the current contact to the current user
    // if ($filter === 'all') {
   
        $stmt = $conn->prepare('SELECT contacts.*,
                                    users.id AS user_id, 
                                    users.firstname AS user_firstname, 
                                    users.lastname AS user_lastname,
                                    users.role AS user_role
                                    FROM contacts 
                                    JOIN users ON contacts.created_by = users.id');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // print_r($result);
    // }
}
?>   

     <table id="contactTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Company</th>
                                <th>Contact Type</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($result  as $row): ?>
                            <tr>
                                <td><?= $row['title'] ?> <?= $row['firstname']?> <?= $row['lastname']?></td>
                                <td> <?= $row['email'] ?> </td>
                                <td> <?= $row['company'] ?> </td>
                                <td> <?= $row['type'] ?>  </td>
                                <td> <button id="contactBtn" current-contact-id="<?= $row['id']?>">View Details </button> </td>

                            </tr>  
                            <?php endforeach; ?>  
                        </tbody>
                    </table>

                