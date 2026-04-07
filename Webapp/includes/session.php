<?php
    session_start();                                        // Start/renew session
$logged_in = $_SESSION['logged_in'] ?? false;           // Is user logged in?

function login($user)                                   // Remember user passed login
    {
session_regenerate_id(true);                        // Update session id
$_SESSION['logged_in'] = true;                      // Set logged_in key to true
$_SESSION['username'] = $user['email'];             // Use email as username
$_SESSION['personID'] = $user['ID'];                // Store Person ID
$_SESSION['role']     = $user['role'];              // Store role for authorization
    }

function require_login($logged_in)                      // Check if user logged in
    {
if ($logged_in == false) {                          // If not logged in
header('Location: login.php');                  // Send to login page
exit;                                           // Stop rest of page running
        }
    }

function logout()                                       // Terminate the session
    {
$_SESSION = [];                                     // Clear contents of array
$params = session_get_cookie_params();              // Get session cookie parameters
setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'],
$params['secure'], $params['httponly']);
session_destroy();                                  // Delete session file
    }

function authenticate(PDO $pdo, string $username, string $password)
    {
$sql = "SELECT * FROM Person
WHERE email = :username";               // Look up by email
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();
if ($user && password_verify($password, $user['password'])) {
return $user;                           // Return user if password matches
        }
return false;                               // Return false if no match
    }
// End of session.php – do NOT add any whitespace, new lines, or closing tag after this line