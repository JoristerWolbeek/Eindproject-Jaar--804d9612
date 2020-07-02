<?php   
session_start();
session_destroy();
setcookie('loggedInUser', "", time() - 300);
sleep(2);
header("Location: index.php");
?>