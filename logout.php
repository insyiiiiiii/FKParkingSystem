<?php
session_start();
session_destroy();

/* Redirect to login page with message */
header("Location: login.php?logout=1");
exit();
?>
