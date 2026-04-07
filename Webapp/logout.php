<?php
require_once 'includes/session.php';

// Destroy the session
session_unset();
session_destroy();

// Redirect straight to login
header('Location: login.php');
exit;
?>