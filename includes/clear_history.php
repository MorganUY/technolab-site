<?php
session_start();
unset($_SESSION['history']);
header('Location: ../profile.php');
exit;
?>