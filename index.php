<?php 

// PUBLIC PAGE

require_once("config.php");
require_once(SRC_DIR . "bobblog.php");

$bb = new BobBlog();

// JS
$bb->addHeadScript(array("src" => "https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"));
$bb->addHeadScript(array("src" => "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"));
$bb->addHeadScript(array("src" => ASSETS_DIR . "js/core.js"));

$bb->addHeadScript(array("script" => 
                         "var homeUrl = \"" . ROOT . "\";" .
                         "var dashboardTemplateDir = \"" . ROOT . SRC_DIR . "html/dashboard/\";" . 
                         "var mode = PUBLIC_ALL_POSTS;"));

// CSS
$bb->addHeadStyle(array("href" => "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css"));
$bb->addHeadStyle(array("href" => "https://fonts.googleapis.com/css?family=Lato"));
$bb->addHeadStyle(array("href" => "https://fonts.googleapis.com/css?family=Droid+Sans|Lato"));
$bb->addHeadStyle(array("href" => ASSETS_DIR . "css/font-awesome.min.css"));
$bb->addHeadStyle(array("href" => ASSETS_DIR . "css/core.css"));


// determine landing page

$uri = $_SERVER['REQUEST_URI'];
$uri = str_replace(BLOG_INSTALL_DIR, "", $uri);
$uri = trim($uri, "/");
$uri = explode("?", $uri)[0];

$main_content = "";
$category_uri = "";

if ($uri === "") {
    ob_start();
    include (SRC_DIR . "blogHomePage.php");
    $main_content = ob_get_clean();
} else {
    ob_start();
    $parts = explode("/", $uri);
    if (sizeof($parts) == 1) {
        // post pages have only one part
        $permalink = $uri;
        include (SRC_DIR . "blogPage.php");
        $main_content = ob_get_clean();
    } else {
        $category_uri = $parts[0];
        $permalink = $parts[1];
        if ($category_uri != CATEGORY_URI){
            // error 404 time
            echo "error 404: invalid category uri";
            ob_get_clean();
        } else {
            $categoryMode = true;
            include (SRC_DIR . "blogHomePage.php");
            $main_content = ob_get_clean();
        }
    }
    
}

// prepare all variables, then load the template
include('mainSiteHTML.html');

