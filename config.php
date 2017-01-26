<?php

session_start();

define("DOMAIN_NAME", "http://localhost/");
define("ROOT_DIR", "bobblog2/");
define("ROOT", DOMAIN_NAME . ROOT_DIR);
define("LOGIN_URL", "http://localhost/bobblog2/login.php");

if (!isset($_SESSION["userID"]) && $_SERVER["REQUEST_URI"] != "/" . ROOT_DIR . "login.php") {
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

define("ACTION_SAVE_POST", "save_new_post");

// database definitions
$blogbase = "blogbase";
$userbase = "userbase";
$content = "content";
$postid = "id";

if (isset($_POST["action"])) {
    include(SRC_DIR . "actionHandler.php");
    handle($_POST["action"]);
}
