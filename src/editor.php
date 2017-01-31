<?php

require_once("../config.php");
require_once(SRC_DIR . "bobblog.php");

$bb = new BobBlog();
$bb->initUser();

$postid = "";
$postcontent = "";
$posttitle = "";
$hasContent = false;

if (isset($_GET["postid"])) {
    $postid = $_GET["postid"];
    $post = $bb->getPost($postid);
    $postcontent = $post->postcontent;
    $posttitle = $post->posttitle;
    $hasContent = true;
}
include (SRC_DIR . 'html/dashboard/editor.html');
