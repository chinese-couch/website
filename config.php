<?php
require_once(__DIR__ . '/google-api/vendor/autoload.php');
$google_client = new Google\Client();
$google_client->setAuthConfig(__DIR__ . '/client_secret.json');
$google_client->addScope("email");
$google_client->addScope("profile");
session_start();
?>
