<?php
function handle(){
    switch ($_POST["action"]) {
        case ACTION_SAVE_POST:
            $post_name = $_POST["name"];
            $post_file = $_POST["file"];
            $post_draft = $_POST["draft"];
            $post_id = $_POST["postid"];
            
            $post_file = htmlentities($post_file, ENT_QUOTES);
            
//            $post_draft = $post_draft ? 1 : 0;
            
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            if ($post_id == "") {
                $sql = "INSERT INTO blogbase (content, posttitle, draft)
                    VALUES (\"$post_file\", \"$post_name\", $post_draft)";
            } else {
                $sql = "UPDATE blogbase SET content='$post_file'
                , posttitle='$post_name'
                , draft='$post_draft' WHERE id=$post_id";
//                $sql = "INSERT INTO blogbase (id, content, posttitle, draft)
//                    VALUES ($post_id, $post_file, $post_name, $post_draft)";
            }
            
            $result = $conn->query($sql);

            if ($result === TRUE) {
                $data = [];
                $data["postid"] = $post_id == "" ? $conn->insert_id : $post_id;
                echo json_encode($data);
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
            exit;
            break;
        case ACTION_SAVE_TAG:
            $tag_name = $_POST["name"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            if (!isset($_POST["change_name"])) {
                $sql = "INSERT INTO tagbase (tagname)
                    VALUES (\"$tag_name\")";
            } else {
//                $sql = "UPDATE tagbase SET content='$post_file'
//                , posttitle='$post_name'
//                , draft='$post_draft' WHERE id=$post_id";
            }
            
            $result = $conn->query($sql);

            if ($result === TRUE) {
                $data = [];
                $data["tag_name"] = $tag_name;
                echo json_encode($data);
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
            exit;
            break;
        case ACTION_SAVE_CAT:
            $cat_name = $_POST["name"];
            
            $post_file = htmlentities($post_file, ENT_QUOTES);
            
//            $post_draft = $post_draft ? 1 : 0;
            
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            if ($post_id == "") {
                $sql = "INSERT INTO blogbase (content, posttitle, draft)
                    VALUES (\"$post_file\", \"$post_name\", $post_draft)";
            } else {
                $sql = "UPDATE blogbase SET content='$post_file'
                , posttitle='$post_name'
                , draft='$post_draft' WHERE id=$post_id";
//                $sql = "INSERT INTO blogbase (id, content, posttitle, draft)
//                    VALUES ($post_id, $post_file, $post_name, $post_draft)";
            }
            
            $result = $conn->query($sql);

            if ($result === TRUE) {
                $data = [];
                $data["postid"] = $post_id == "" ? $conn->insert_id : $post_id;
                echo json_encode($data);
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
            break;
            exit;
        default:
            exit;
            ;
        }
}
