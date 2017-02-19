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
            $post_id = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $sql = "INSERT INTO tagbase (tagname) VALUES (\"$tag_name\")";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                $data = [];
                $data["tag_name"] = $tag_name;
                $data["tag_id"] = $conn->insert_id;
                
                // update the tagblogmap
                $sql = "INSERT INTO tagblogmap (postid, tagid) VALUES ($post_id, $conn->insert_id)";
                $result = $conn->query($sql);
                if ($result === TRUE) {
                    echo json_encode($data);
                } else {
                    // failed
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            $conn->close();
            exit;
            break;
        case ACTION_ADD_TAG_TO_POST:
            $tagid = $_POST["tagid"];
            $postid = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // to do: add binding here
            $sql = "INSERT INTO tagblogmap (tagid, postid)
                VALUES (\"$tagid\", \"$postid\")";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                echo ("Success");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            exit;
            break;
        case ACTION_REMOVE_TAG_FROM_POST:
            $tagid = $_POST["tagid"];
            $postid = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // to do: add binding here
            $sql = "DELETE FROM tagblogmap
                WHERE tagid = $tagid AND postid = $postid";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                echo ("Success");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            exit;
            break;
        case ACTION_SAVE_CAT:
            $cat_name = $_POST["name"];
            $post_id = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $sql = "INSERT INTO catbase (catname) VALUES (\"$cat_name\")";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                $data = [];
                $data["cat_name"] = $cat_name;
                $data["cat_id"] = $conn->insert_id;
                
                // update the tagblogmap
                $sql = "UPDATE blogbase SET category = $conn->insert_id WHERE id = $post_id";
                $result = $conn->query($sql);
                if ($result === TRUE) {
                    echo json_encode($data);
                } else {
                    // failed
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            $conn->close();
            exit;
            break;
        case ACTION_ADD_CAT_TO_POST:
            $catid = $_POST["catid"];
            $postid = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // to do: add binding here
            $sql = "UPDATE blogbase SET category = $catid WHERE id = $postid";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                echo ("Success");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            exit;
            break;
            exit;
            break;
        case ACTION_REMOVE_CAT_FROM_POST:
            $catid = $_POST["catid"];
            $postid = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // to do: add binding here
            $sql = "UPDATE blogbase SET category = NULL WHERE id = $postid";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                echo ("Success");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            exit;
            break;
            exit;
            break;
        case ACTION_TAGS_BY_POSTID:
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $post_id = $_POST["postid"];
            
            $sql = "SELECT tagname FROM tagblogview WHERE postid = $post_id";
            
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
    $title = "Draft Created on " . $date;
    $isDraft = true;
    $sql = "INSERT INTO blogbase (content, posttitle, draft)
            VALUES (\"\", \"$title\", $isDraft)";
    
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

    $sql = "SELECT tagname FROM tagblogview WHERE postid = $postid";

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
