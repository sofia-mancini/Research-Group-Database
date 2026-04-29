<?php
// includes/session.php

session_start();
$logged_in = $_SESSION['logged_in'] ?? false;

/**
 * Standardizes session data upon successful authentication.
 * Uses 'person_id' as the consistent key for database foreign key lookups.
 */
function login(array $user): void
{
    session_regenerate_id(true); 
    $_SESSION['logged_in'] = true;
    $_SESSION['username']  = $user['name'];
    $_SESSION['role']      = $user['role'];
    
    // Fix: Capture the primary key from the 'person' table
    // Cast to int to satisfy type hinting in auth.php functions
    $_SESSION['person_id'] = (int)$user['ID']; 
}

function logout(): void
{
    $_SESSION = [];
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    session_destroy();
}

function require_login(bool $logged_in): void
{
    if ($logged_in === false) {
        header('Location: login.php');
        exit;
    }
}