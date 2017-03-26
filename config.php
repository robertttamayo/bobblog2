<?php

session_start();

define("DOMAIN_NAME", "http://localhost/");
define("ROOT_DIR", "bobblog2/");
define("ROOT", DOMAIN_NAME . ROOT_DIR);
define("LOGIN_URL", ROOT . "login.php");
define("BLOG_INSTALL_DIR", "bobblog2/");

define("SRC_DIR", __DIR__ . '/src/');
define("MEDIA_DIR", __DIR__ . '/media/');
define("MEDIA_URL", ROOT . 'media/');
define("TEMPLATE_DIR", 'src/html/template/');
define("ASSETS_DIR", ROOT . 'assets/');
define("MAIN_SITE_TEMPLATE_URL", ROOT. "mainSiteHTML.html");

define("DB_SERVER", "localhost");
define("DB_NAME", "bobblog_db");
define("DB_USERNAME", "bob");
define("DB_PASSWORD", "b0b6l0g");

define("ADMIN", "bob");
define("ADMIN_PASSWORD", "bob");

define("SITE_NAME", "TheNoticed.com");

define("ACTION_SAVE_POST", "save_new_post");
define("ACTION_SAVE_TAG", "save_tag");
define("ACTION_SAVE_CAT", "save_cat");
define("ACTION_TAGS_BY_POSTID", "tags_by_postid");
define("ACTION_ADD_TAG_TO_POST", "add_tag_to_post");
define("ACTION_REMOVE_TAG_FROM_POST", "remove_tag_from_post");
define("ACTION_ADD_CAT_TO_POST", "add_cat_to_post");
define("ACTION_REMOVE_CAT_FROM_POST", "remove_cat_from_post");
define("ACTION_UPLOAD_IMAGE", "upload_image");
define("ACTION_POST_DRAFT_STATUS", "post_draft_status");

define("PERMALINK_STRUCTURE_CATEGORY", "category");
define("CATEGORY_URI", "category");

$blog_settings = array();
$blog_settings["permalink_structure"] = PERMALINK_STRUCTURE_CATEGORY;

// database definitions
$blogbase = "blogbase";
$userbase = "userbase";
$content = "content";
$postid = "id";

if (isset($_POST["action"])) {
    // don't allow post requests unless admin user is signed in
    if (!isset($_SESSION["userID"])) {
        exit;
        return;
    }
    include(SRC_DIR . "actionHandler.php");
    handle($_POST["action"]);
}
