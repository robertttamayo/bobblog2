<?php

require_once(SRC_DIR . 'helpers/userProfile.php');

class BobBlog {
    
    private $headScripts = array();
    private $headStyles = array();
    private $title = "Title";
    private $db_con;
    private $userProfile;
    private $post = array();
    private $tags = array();

    public function __construct(){
        //$this->db_con = new PDO("mysql:host={DB_SERVER};dbname={DB_NAME}", BD_USERNAME, DB_PASSWORD);
        $this->getTags(null);
    }
    
    public function headScripts(){
        foreach($this->headScripts as $script) {
            foreach($script as $key => $value){
                switch($key) {
                    case "src":
                        echo "<script src=\"{$script["src"]}\"></script>";
                        break;
                    case "script":
                        echo "<script>" . $script["script"] . "</script>";
                        break;
                    default:
                }
            }
            echo "
    ";
        }
    }
    public function headStyles(){
        foreach($this->headStyles as $style) {
            echo "<link rel=\"stylesheet\" href=\"{$style["href"]}\"/>";
        }
    }
    public function addHeadScript($script){
        $this->headScripts[] = $script;
    }
    public function addHeadStyle($style){
        $this->headStyles[] = $style;
    }
    public function title(){
        echo $this->title;
    }
    public function initUser() {
        $params = array();
        
        $params["name"] = $_SESSION["userID"];
        $params["role"] = $_SESSION["userRole"];
        $params["type"] = $_SESSION["userType"];
        $params["email"] = $_SESSION["userEmail"];
        
        $this->userProfile = new UserProfile($params);
    }
    public function getUserProfile(){
        return $this->userProfile;
    }
    public function getPost($postid){
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM blogbase WHERE id = $postid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $this->post["postid"] = $row["id"];
                $this->post["content"] = $row["content"];
            }
        } else {
            echo "0 results";
        }
        
        $conn->close();
        return $this->post;
    }
    public function getTags($postid){
        if ($postid == null) {
            
        }
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM tagbase";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $this->tags[] = ["name" => $row["tagname"], "id" => $row["id"]];
            }
        } else {
            echo "0 results";
        }
        
//        $sql = "SELECT postid FROM tabblogbase"
//        $result = $conn->query($sql);
//        
//        if ($result->num_rows > 0) {
//            // output data of each row
//            while($row = $result->fetch_assoc()) {
//                $this->tags[] = $row["tagname"];
//                
//            }
//        } else {
//            echo "0 results";
//        }
        
        $conn->close();
    }
}

mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
