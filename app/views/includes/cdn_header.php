<?php
// Same base-path detection as Router::stripBasePath(), so relative links/assets
// resolve correctly whether the app runs at the domain root (live) or in a
// subfolder (e.g. local XAMPP htdocs/citytechstore.org).
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$baseHref = ($basePath === '') ? '/' : $basePath . '/';
?>
<base href="<?php echo $baseHref; ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
    integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Reddit+Sans:ital,wght@0,200..900;1,200..900&family=Outfit:wght@600;700;800&family=Geist:wght@400;500;600;700&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="assets/css/theme.css">
<link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png">
<link rel="apple-touch-icon" sizes="72x72" href="assets/img/icons/icon-72x72.png">
<link rel="apple-touch-icon" sizes="96x96" href="assets/img/icons/icon-96x96.png">
<link rel="apple-touch-icon" sizes="128x128" href="assets/img/icons/icon-128x128.png">
<link rel="apple-touch-icon" sizes="144x144" href="assets/img/icons/icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="assets/img/icons/icon-152x152.png">
<link rel="apple-touch-icon" sizes="192x192" href="assets/img/icons/icon-192x192.png">
<link rel="apple-touch-icon" sizes="384x384" href="assets/img/icons/icon-384x384.png">
<link rel="apple-touch-icon" sizes="512x512" href="assets/img/icons/icon-512x512.png">
<link rel="apple-touch-icon" sizes="180x180" href="assets/img/icons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/favicon-16x16.png">
<link rel="mask-icon" href="assets/img/icons/safari-pinned-tab.svg" color="#5bbad5">
<link rel="shortcut icon" href="assets/img/icons/favicon.ico">
<meta name="msapplication-TileColor" content="#2d89ef">
<meta name="msapplication-config" content="assets/img/icons/browserconfig.xml">
<meta name="theme-color" content="#ffffff">


<link rel="manifest" href="../webmanifest.json">
<style>
    .reddit-sans-font-200 {
        font-family: "Reddit Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 200;
        font-style: normal;
    }

    .reddit-sans-font-300 {
        font-family: "Reddit Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 300;
        font-style: normal;
    }

    .reddit-sans-font-400 {
        font-family: "Reddit Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 400;
        font-style: normal;
    }

    .reddit-sans-font-500 {
        font-family: "Reddit Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 500;
        font-style: normal;
    }

    .reddit-sans-font-600 {
        font-family: "Reddit Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 600;
        font-style: normal;
    }

    .reddit-sans-font-700 {
        font-family: "Reddit Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 700;
        font-style: normal;
    }

    .reddit-sans-font-800 {
        font-family: "Reddit Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 800;
        font-style: normal;
    }

    .reddit-sans-font-900 {
        font-family: "Reddit Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 900;
        font-style: normal;
    }


    .product-device {
        position: absolute;
        right: 10%;
        bottom: -30%;
        width: 300px;
        height: 540px;
        background-color: #333;
        border-radius: 21px;
        transform: rotate(30deg);
    }

    .product-device::before {
        position: absolute;
        top: 10%;
        right: 10px;
        bottom: 10%;
        left: 10px;
        content: "";
        background-color: rgba(255, 255, 255, .1);
        border-radius: 5px;
    }

    .product-device-2 {
        top: -25%;
        right: auto;
        bottom: 0;
        left: 5%;
        background-color: #e5e5e5;
    }

    .blur-bg {
        /* background-color: rgba(255, 255, 255, 0.4); */
        backdrop-filter: blur(10px);
    }
</style>

<?php require_once ('./config/config.php'); ?>