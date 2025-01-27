
<?php
session_start();
session_unset();  // Clear all session variables
session_destroy();  // Destroy the session
header("Location: ../pages/signin.php");  // Redirect to sign-in page after logging out
exit();
?>
