<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['performFunction'])) {
    $performFunction = $_POST['performFunction'];

    if ($performFunction == "logoutGoogle") {
        $tempToken = $_SESSION['google_token'];
        echo  json_encode([
            'success' => true,
            'data' => $tempToken
        ]);
        unset($_SESSION['google_user']);
        unset($_SESSION['google_token']);
    }
} else {
    http_response_code(400);
    echo 'Invalid request';
}
?>