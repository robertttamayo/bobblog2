<?php

session_start();

define("ROOT", "http://localhost/bobblog2/");
define("LOGIN_URL", "http://localhost/bobblog2/login.php");

if (!isset($_SESSION["userID"]) && $_SERVER["REQUEST_URI"] != ROOT . "login.php") {
    header("Location: " . LOGIN_URL);
    die;
}

define("SRC_DIR", 'src/');
define("MEDIA_DIR", 'media/');
define("TEMPLATE_DIR", 'src/html/template/');
define("ASSETS_DIR", 'assets/');

define("DB_SERVER", "localhost");
define("DB_NAME", "bobblog_db");
define("DB_USERNAME", "bob");
define("DB_PASSWORD", "b0b6l0g");

define("ADMIN", "bob");
define("ADMIN_PASSWORD", "bob");

define("SITE_NAME", "TheNoticed.com");
