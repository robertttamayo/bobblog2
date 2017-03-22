<?php

$bb = new BobBlog();
$bb->initPosts();
$posts = $bb->getPosts();
$sorted_posts = sortPosts($posts);

function sortPosts($posts){

    foreach($posts as $post){
        if ($post->draft) {
            continue;
        }
        $sorted[] = $post;
        $dates[] = $post->publishdate;
    }

    // Sort the data with volume descending, edition ascending
    // Add $data as the last parameter, to sort by the common key
    array_multisort($dates, $sorted);

    return $sorted;
}

include (SRC_DIR . 'html/template/blog_page.html');
