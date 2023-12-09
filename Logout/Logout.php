<?php
    session_start();
    // Destroy the session
    session_destroy();
    header('Location: Login.html'); //login Page link here
    exit();
?>