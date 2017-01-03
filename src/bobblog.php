<?php

require_once(SRC_DIR . 'helpers/userProfile.php');

class BobBlog {
    
    private $headScripts = array();
    private $headStyles = array();
    private $title = "Title";
    private $db_con;
    private $userProfile;

    public function __construct(){
        //$this->db_con = new PDO("mysql:host={DB_SERVER};dbname={DB_NAME}", BD_USERNAME, DB_PASSWORD);
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
}

mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
