<?php
session_start();
$host = 'localhost:3307';
$username = 'DolphinAdmin';
$password = 'password123';
$dname = 'dolphin_crm';
$sessionId = session_id();
$conn = new PDO("mysql:host=$host;dbname=$dname;charset=utf8mb4", $username, $password);

$stmt = $conn->query("SELECT * FROM contacts");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($results);
$result = '';

$current_user_id =  $_SESSION['user_id'];


//GET Request to load page
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filter'])) {
       
    $filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING);

     // Assign the current contact to the current user
    if ($filter === 'all') {
   
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
    }

    if ($filter === 'sales') {
   
        $stmt = $conn->prepare('SELECT contacts.*,
                                    users.id AS user_id, 
                                    users.firstname AS user_firstname, 
                                    users.lastname AS user_lastname,
                                    users.role AS user_role
                                    FROM contacts 
                                    JOIN users ON contacts.created_by = users.id
                                    WHERE contacts.type = \'Sales Lead\' ');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if ($filter === 'support') {
   
        $stmt = $conn->prepare('SELECT contacts.*,
                                    users.id AS user_id, 
                                    users.firstname AS user_firstname, 
                                    users.lastname AS user_lastname,
                                    users.role AS user_role
                                    FROM contacts 
                                    JOIN users ON contacts.created_by = users.id
                                    WHERE contacts.type = \'Support\' ');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if ($filter === 'assigned') {
   
        $stmt = $conn->prepare('SELECT contacts.*,
                                    users.id AS user_id, 
                                    users.firstname AS user_firstname, 
                                    users.lastname AS user_lastname,
                                    users.role AS user_role
                                    FROM contacts 
                                    JOIN users ON contacts.created_by = users.id
                                    WHERE users.id = :current_user_id ');
        $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
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

                