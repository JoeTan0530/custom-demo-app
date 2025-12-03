<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_data'])) {
    $userData = json_decode($_POST['user_data'], true);
    $tokenData = $_POST['token_data'];
    if ($userData) {
        $_SESSION['google_user'] = $userData;
        $_SESSION['google_token'] = $tokenData;
        echo 'User data saved';
    }
} else {
    http_response_code(400);
    echo 'Invalid request';
}
?>