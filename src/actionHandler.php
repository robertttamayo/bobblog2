<?php
function handle(){
    switch ($_POST["action"]) {
        case ACTION_SAVE_POST:
            $post_name = $_POST["name"];
            $post_file = $_POST["file"];
            $post_draft = $_POST["draft"];
            $post_id = $_POST["postid"];

            echo "post_name: " . $post_name;
            echo "post_file: " . $post_file;
            echo "post_draft: " . $post_draft;
            echo "post_id: " . $post_id;
            exit;
            break;
        default:;

    }
}
