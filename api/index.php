<?php

if (!isset($_GET['key'])) {
    echo "{error: 'No Key Set'}";
    exit;
}
if (!isset($_GET['type'])) {
    echo "{error: 'No Type Set'}";
    exit;
}

require_once("../config.php");

$db_server = DB_SERVER;
$db_username = DB_USERNAME;
$db_password = DB_PASSWORD;
$db_name = DB_NAME;

/**

LEFT JOIN if no category is set.
JOIN if category is set
always include AND blogbase.draft = 0

*/

$param_values = [];
$sql = "";
$sql_catbase_join = ""; // will either be LEFT JOIN or JOIN
$order_by = " blogbase.id ";

$type = filter_var($_GET['type'], FILTER_SANITIZE_STRING);
if ($type == 'post') {
    debug("type = post");
    // category / standard
    if (isset($_GET['category'])) {
        if (filter_var($_GET['category'], FILTER_VALIDATE_INT)) {
            // set variables
            $category = $_GET['category'];
            settype($category, 'integer');
            $sql_catbase_join = ' JOIN catbase '; // needs to match a category
            
            // construct sql
            $sql = "SELECT blogbase.*, catbase.*
            FROM blogbase
                $sql_catbase_join
                ON blogbase.category = catbase.catid
            WHERE blogbase.category = $category AND blogbase.draft = 0
            ORDER BY $order_by
            ";
        }
    } else if (isset($_GET['category_permalink'])) {
        $param_values[] = [
            'key' => ':category_permalink',
            'value' => filter_var($_GET['category_permalink'], FILTER_SANITIZE_STRING)
        ];
        
        $category_permalink = filter_var($_GET['category_permalink'], FILTER_SANITIZE_STRING);
        
        $sql_catbase_join = ' JOIN catbase '; // needs to match a category

        // construct sql
        $sql = "SELECT blogbase.*, catbase.*
        FROM blogbase
            $sql_catbase_join
            ON blogbase.category = catbase.catid
        WHERE catbase.catpermalink = :category_permalink AND blogbase.draft = 0
        ORDER BY $order_by
        ";
    } else {
        // set variables
        $sql_catbase_join = ' LEFT JOIN catbase '; // doesn't need to have a category

        // construct sql
        $sql = "SELECT blogbase.*, catbase.* 
        FROM blogbase
            $sql_catbase_join
            ON blogbase.category = catbase.catid 
        WHERE blogbase.draft = 0
        ORDER BY $order_by
        ";
    }
    // limit
    if (isset($_GET['limit'])) {
        if (isset($_GET['offset'])) {
            $offset = 0;
            $limit = 0;
            if (filter_var($_GET['limit'], FILTER_VALIDATE_INT)) {
                $limit = $_GET['limit'];
                settype($limit, 'integer');
            }
            if (filter_var($_GET['offset'], FILTER_VALIDATE_INT)) {
                $offset = $_GET['offset'];
                settype($offset, 'integer');
            }

            $sql .= " LIMIT $offset , $limit ";
        } else {
            $limit = 0;
            if (filter_var($_GET['limit'], FILTER_VALIDATE_INT)) {
                $limit = $_GET['limit'];
                settype($limit, 'integer');
            }
            $sql .= " LIMIT $limit ";
        }
    }
    // postid, overrides category and limit
    if (isset($_GET['postid'])) {
        if (filter_var($_GET['postid'], FILTER_VALIDATE_INT)) {
            // set variables
            $postid = $_GET['postid'];
            settype($postid, 'integer');
            $sql_catbase_join = ' LEFT JOIN catbase ';
            
            // prepare sql
            $sql = " SELECT blogbase.*, catbase.* 
            FROM blogbase
                $sql_catbase_join
                ON blogbase.category = catbase.catid 
            WHERE blogbase.id = $postid AND blogbase.draft = 0 
            LIMIT 1
            "; 

        }
    }
    // permalink, overrides cateogry and limit
    if (isset($_GET['permalink'])) {
        
        $param_values[] = [
            'key' => ':permalink',
            'value' => filter_var($_GET['permalink'], FILTER_SANITIZE_STRING)
        ];
        
        // set variables
        $permalink = filter_var($_GET['permalink'], FILTER_SANITIZE_STRING);
        $sql_catbase_join = ' LEFT JOIN catbase ';
 
        $sql = " SELECT blogbase.*, catbase.*
        FROM blogbase
            $sql_catbase_join
            ON blogbase.category = catbase.catid
        WHERE blogbase.permalink = :permalink AND blogbase.draft = 0
        LIMIT 1
        ";
    }
    $data = executeSQL();
    finish($data);
    
// if type == category
} else if ($type == 'category') {
    debug("type = category");
    
    $sql = "SELECT * FROM catbase";
    $data = executeSQL();
    finish($data);
}

// tags
if (isset($_GET['tags'])) {
    $param_values[] = [
        'key' => ':tag',
        'value' => filter_var($_GET['tag'], FILTER_SANITIZE_STRING)
        ];
}

// date from
if (isset($_GET['date_from'])) {
    $param_values[] = [
        'key' => ':date_from',
        'value' => filter_var($_GET['date_from'], FILTER_SANITIZE_STRING)
        ];
}

// date to
if (isset($_GET['date_to'])) {
    $param_values[] = [
        'key' => ':date_to',
        'value' => filter_var($_GET['date_to'], FILTER_SANITIZE_STRING)
        ];
    
}

// month
if (isset($_GET['month'])) {
    $param_values[] = [
        'key' => ':month',
        'value' => filter_var($_GET['month'], FILTER_SANITIZE_STRING)
        ];
}

function executeSQL(){
    debug("executeSQL()");
    global $sql;
    global $param_values;
    global $db_server;
    global $db_username;
    global $db_password;
    global $db_name;
    
    try {
        $conn = new PDO("mysql:host=$db_server;dbname=$db_name", $db_username, $db_password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        debug($sql . '<br>');
        $stmt = $conn->prepare($sql);

        $size = sizeof($param_values);
        for ($i = 0; $i < $size; $i++) {
            $key = $param_values[$i]['key'];
            $value = $param_values[$i]['value'];
            $stmt->bindParam($key, $value);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = json_encode($rows);
        $params = json_encode($param_values);

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    $conn = null;
    
    return $data;
}

function finish($data) {
//    logData(json_encode($data));
    
    echo ($data);
    exit;
}
function logData($data){
    ?>
    <script>

        var data = <?= json_encode($data) ?>;
        console.log(data);

    </script>
    <?php       
}

function debug($message) {
    return;
    ?>
    <div style="background: #fcc; margin: auto; padding: 10px; border: 1px solid red; margin-bottom: 20px; margin-top: 20px;">
        <?= $message ?>
    </div>
    <?php
}
?>



<?php
    debug("at the end where it should not be");
    exit;
    try {
    $conn = new PDO("mysql:host=$db_server;dbname=$db_name", $db_username, $db_password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
//    $sql = " SELECT blogbase.posttitle, catbase.catname FROM blogbase, catbase WHERE blogbase.category = catbase.catid LIMIT 0, 5 ";
    $sql = "
        SELECT blogbase.posttitle, blogbase.id, catbase.catname
        FROM blogbase
        LEFT JOIN catbase
        ON blogbase.category = catbase.catid AND blogbase.category = 1 AND draft = 0
        ORDER BY blogbase.id
        LIMIT 0, 5
    ";
    $stmt = $conn->prepare($sql);

    $stmt->execute();
    
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($rows as $row) {
        echo '<div style="border: 1px solid #ccc; background: #f5f5f5; margin-bottom: 20px; padding: 10px;">';
        foreach($row as $key => $value) {
            echo 'key: ' . $key . '<br>';
            echo 'value: ' . $value . '<br><br>';
        }
        echo '</div>';
    }
    exit;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


