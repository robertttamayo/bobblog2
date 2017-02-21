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
    $postcontent = $post->content;
    $posttitle = $post->title;
    $hasContent = true;
} else {
    //must save this new draft
    include_once (SRC_DIR . "actionHandler.php");
    $postid = saveNewDraft();
//    $_POST["postid"] = $postid;
//    $_POST["action"] = ACTION_TAGS_BY_POSTID;
//    handle($_POST["action"]);
}
include (SRC_DIR . 'html/dashboard/editor.html');
