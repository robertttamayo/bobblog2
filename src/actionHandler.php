<?php
function handle(){
    switch ($_POST["action"]) {
        case ACTION_SAVE_POST:
            $post_name = $_POST["name"];
            $post_file = $_POST["file"];
            $post_draft = $_POST["draft"];
            $post_id = $_POST["postid"];
            
//            $post_draft = $post_draft ? 1 : 0;
            
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            if ($post_id == "") {
                $sql = "INSERT INTO blogbase (content, posttitle, draft)
                    VALUES (\"$post_file\", \"$post_name\", $post_draft)";
            } else {
                $sql = "INSERT INTO blogbase (id, content, posttitle, draft)
                    VALUES ($post_id, $post_file, $post_name, $post_draft)";
            }
            
            $result = $conn->query($sql);

            if ($result === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
            

                exit;
                break;
            default:;

        }
}
