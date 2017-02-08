<?php
/** Handle a POST request from an ajax call */
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
                $data["tag_id"] = $conn->insert_id;
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
        case ACTION_TAGS_BY_POSTID:
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $post_id = $_POST["postid"];
            
            $sql = "SELECT tagname FROM tagblogview WHERE postid = 3";
            
            $result = $conn->query($sql);
            
            if ($result != false) {
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<br>tagname: " . $row["tagname"];
                    }
                } else {
                    echo "Did not find any tags for this post";
                }
            } else {
                echo "Did not find any tags for this post";
            }
            

            $conn->close();
            break;
            exit;
        default:
            exit;
            ;
        }
}
/** Functions called manually by model files */
function saveNewDraft() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $date = date("F j, Y");
    $isDraft = true;
    $sql = "INSERT INTO blogbase (content, posttitle, draft)
            VALUES (\"\", \"$date\", $isDraft)";
    
    $result = $conn->query($sql);

    if ($result === TRUE) {
        $post_id = $conn->insert_id;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    return $post_id;
}
function loadTagsByPostId($postid) {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT tagbase.tagname FROM tagbase, blogbase, tagblogmap
            WHERE tagbase.tagid = tagblogmap.tagid
            AND tagblogmap.postid = 3";


    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<br>tagname:" . $row["tagname"];
        }
    } else {
        echo "Did not find any tags for this post";
    }

    $conn->close();
}
function updateTagsByPostId() {
    
}
