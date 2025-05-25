<!-- filepath: c:\xampp\htdocs\ApexPlanet_Internship_May_25_Proj\Task_4\Security_Enhancements\logout.php -->
<?php
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the welcome page with a logout confirmation message
header("Location: index.php?msg=You+have+been+logged+out+successfully");
exit();
?>