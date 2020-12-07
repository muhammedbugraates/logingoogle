<?php
    require_once 'vendor/autoload.php';

    $gClient = new Google_Client();
    $gClient->setClientId("__YOUR_CLIENT_ID__");
    $gClient->setClientSecret("__YOUR_CLIENT_SERVER__");

    $gClient->setApplicationName("__YOUR__APP_NAME__");
    $gClient->setRedirectUri("__YOUR_RETURN__ADDRESS__");

    // specify to values to get from Google
    // You do not have to change these values.
    $gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");

    $login_url = $gClient->createAuthUrl();
