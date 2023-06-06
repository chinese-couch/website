<?php

//Include Google Configuration File
include('../config.php');

if (!isset($_SESSION['access_token'])) {
  // Create a URL to obtain user authorization
  $google_login_btn = '<a href="' . $google_client->createAuthUrl() . '"><button class="btn btn-primary">Login with Google</button></a>';
} else {
  header("Location: home.php");
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Under Construction</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
  <meta charset="utf-8" />
  <meta content="Default page" name="description" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png" />
  <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png" />
  <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png" />
  <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png" />
  <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png" />
  <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png" />
  <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png" />
  <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png" />
  <link rel="icon" type="image/png" sizes="192x192" href="/android-icon-192x192.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png" />
  <link rel="manifest" href="/manifest.json" />
  <meta name="msapplication-TileColor" content="#ffffff" />
  <meta name="msapplication-TileImage" content="/ms-icon-144x144.png" />
  <meta name="theme-color" content="#ffffff" />
</head>

<body>
  <div class="container" style="margin-top: 100px">
    <img src="./img/logo.png" alt="Logo" style="
          display: table;
          margin: 0 auto;
          max-width: 100px;
          border-radius: 10px;
        " />
    <div style="text-align: center;">
      <h3>ChineseCouch</h3>
    </div>
    <?php
    echo '<div align="center">' . $google_login_btn . '</div>';
    ?>

  </div>
</body>

</html>