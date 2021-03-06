<?php 

// DEVELOPING DASHBOARD

require_once("config.php");
require_once(SRC_DIR . "bobblog.php");

$bb = new BobBlog();
$bb->initUser();
//$bb->getUserProfile()->print_user();

// JS
$bb->addHeadScript(array("src" => "https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"));
$bb->addHeadScript(array("src" => "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"));
$bb->addHeadScript(array("src" => ASSETS_DIR . "js/core.js"));
$bb->addHeadScript(array("src" => ASSETS_DIR . "js/editor.js"));
$bb->addHeadScript(array("src" => ASSETS_DIR . "js/imgedit.js"));

$bb->addHeadScript(array("script" => 
                         "var homeUrl = \"" . ROOT . "\";" .
                         "var dashboardTemplateDir = \"" . ROOT . SRC_DIR . "html/dashboard/\";" . 
                         "var mode = WELCOME;"));

// CSS
$bb->addHeadStyle(array("href" => "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css"));
$bb->addHeadStyle(array("href" => "https://fonts.googleapis.com/css?family=Lato"));
$bb->addHeadStyle(array("href" => "https://fonts.googleapis.com/css?family=Droid+Sans|Lato"));
$bb->addHeadStyle(array("href" => ASSETS_DIR . "css/font-awesome.min.css"));
$bb->addHeadStyle(array("href" => ASSETS_DIR . "css/core.css"));

// prepare all variables, then load the template
include(TEMPLATE_DIR . 'dashboard.html');
