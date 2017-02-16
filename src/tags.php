<?php

require_once("../config.php");
require_once(SRC_DIR . "bobblog.php");

$bb = new BobBlog();

$all_tags = $bb->getAllTags();
$tags = $bb->getTags($bb->getPostId());

echo "<pre>";
echo "all tags <br>";
print_r($all_tags);
echo "tags <br>";
print_r($tags);
echo "</pre>";
echo $all_tags[0]["id"];
for ($i = 0; $i < sizeof($all_tags); $i++) {
    for ($j = 0; $j < sizeof($tags); $j++) {
        echo $tags[$j]["id"] . "<br>" . $all_tags[$i]["id"];
        if ($tags[$j]["id"] == $all_tags[$i]["id"]) {
            $all_tags[$i]["active"] = true;
        }
    }
}

include (SRC_DIR . 'html/dashboard/tags.html');
