
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $validUsername = "demo";
    $validPassword = "password";

    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === $validUsername && $password === $validPassword) {
        // Successful login - direct to next page
        echo "Login successful! Welcome, $username!";
    } else {
        // Invalid login
        echo "Invalid username or password. Please try again.";
    }
}
?>
