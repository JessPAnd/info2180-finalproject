<?php
    session_start();
    // Destroy the session
    session_destroy();
    header('Location: ../login-page.html');   //login Page link here
    exit();
?>
