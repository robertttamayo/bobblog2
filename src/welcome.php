<?php

require_once("../config.php");
require_once(SRC_DIR . "bobblog.php");

$bb = new BobBlog();
$bb->initPosts();
$posts = $bb->getPosts();

include (SRC_DIR . 'html/dashboard/welcome.html');
