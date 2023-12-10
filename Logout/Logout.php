<?php
    session_start();
    // Destroy the session
    session_destroy();
    header('Location: ../login.php');   //login Page link here
    exit();
?>
