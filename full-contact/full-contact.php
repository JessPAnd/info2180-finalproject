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
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['contact_id'])) {
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

    <?php else: ?>
        <p>No contact found.</p>
    <?php endif; ?>


        <ul>
            <?php foreach ($result[0] as $key => $value): ?>
                <li><?= $key ?>: <?= $value ?></li>
            <?php endforeach; ?>
        </ul>

</div>

