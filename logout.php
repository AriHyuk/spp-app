<?php
session_start();

// Destroy session
session_destroy();

// Redirect ke login
header("Location: login.php?logout=1");
exit();
?>
