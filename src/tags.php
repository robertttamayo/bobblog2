<?php

require_once("../config.php");
require_once(SRC_DIR . "bobblog.php");

$bb = new BobBlog();

include (SRC_DIR . 'html/dashboard/tags.html');
