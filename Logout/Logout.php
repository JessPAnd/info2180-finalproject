<?php
    session_start();
    // Destroy the session
    session_destroy();
    header('Location: Login.php); //login Page link here
    exit();
?>
