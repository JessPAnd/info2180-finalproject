<?php
    $f_name = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $l_name = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);


        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)) {
            die('Password must have at least one number, one letter, one capital letter, and be at least 8 characters long.');
        }

        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        $host = 'localhost';
        $dbname = 'dolphin_crm';
        $username_db = 'admin';
        $password_db = 'password123';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);

        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, password, email, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$f_name, $l_name,$hash_password, $email, $role]);
        $pdo = null;


        header('Location: dashboard.html');
    ?>