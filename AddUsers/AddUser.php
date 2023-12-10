<?php
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = isset($_POST['fname']) ? filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING) : '';
    $lname = isset($_POST['lname']) ? filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING) : '';
    $email = isset($_POST['email']) ? filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) : '';
    $password = isset($_POST['password']) ? filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) : '';
    $role = isset($_POST['role']) ? filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING) : '';
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)) {
        print('Password must have at least one number, one letter, one capital letter, and be at least 8 characters long.');
    }

    if (!$email) {
        print('Invalid email address.');
    }

    $hash_password = password_hash($password, PASSWORD_BCRYPT);

    $host = 'localhost';
    $dbname = 'dolphin_crm';
    $username_db = 'DolphinAdmin';
    $password_db = 'password123';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, password, email, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$fname, $lname, $hash_password, $email, $role]);
        echo "New user added successfully";
    } 
    catch(PDOException $e) 
    {
        echo "Error: " . $e->getMessage();
    }
    
    $pdo = null;
}
?>
