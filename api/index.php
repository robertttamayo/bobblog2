<?php

if (!isset($_GET['key'])) {
    exit;
}

require_once("../config.php");

// simple API to return blog posts based on criteria
$sql = "";
$sql = "SELECT * FROM blogbase ";

$param_values = [];
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

// category
if (isset($_GET['category'])) {
    $sql .= " AND category = :category ";
    $param_values[] = [
        'key' => ':category',
        'value' => filter_var($_GET['category'], FILTER_SANITIZE_STRING)
        ];
    
}

// tags
if (isset($_GET['tags'])) {
    $param_values[] = [
        'key' => ':tags',
        'value' => filter_var($_GET['category'], FILTER_SANITIZE_STRING)
        ];
}

// offset
if (isset($_GET['offset']) && !isset($_GET['limit'])) {
    $sql .= " OFFSET :offset ";
    $param_values[] = [
        'key' => ':offset',
        'value' => filter_var($_GET['offset'], FILTER_SANITIZE_NUMBER_INT)
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
$db_server = DB_SERVER;
$db_username = DB_USERNAME;
$db_password = DB_PASSWORD;
$db_name = DB_NAME;

try {
    $conn = new PDO("mysql:host=$db_server;dbname=$db_name", $db_username, $db_password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo $sql . '<br>';
    $stmt = $conn->prepare($sql);

    $size = sizeof($param_values);
    for ($i = 0; $i < $size; $i++) {
        echo $param_values[$i]['key'] . '<br>';
        echo $param_values[$i]['value'] . '<br><br>';
        $key = $param_values[$i]['key'];
        $value = $param_values[$i]['value'];
        $stmt->bindParam($key, $value);
    }
    var_dump($stmt);
//    $stmt = $conn->prepare('select * from blogbase limit 7 ,5');
    $stmt->execute();
    
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $data = json_encode($rows);
    $params = json_encode($param_values);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null;

?>

<script>
    var params = <?= $params ?>;
    console.log(params);
    var data = <?= $data ?>;
    console.log(data);
    
</script>


