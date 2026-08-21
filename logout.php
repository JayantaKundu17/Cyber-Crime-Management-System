<?php
session_start();
session_destroy();
header("Location: ../index.html"); // Redirects to index.html in root folder
exit();
?>
