<?php

if (!isset($_GET['key'])) {
    exit;
}

// simple API to return blog posts based on criteria
$sql = "";
$sql = "SELECT * FROM blogbase ";

$param_values = [];
$param_types = [];
// limit
if (isset($_GET['limit'])) {
    $sql .= " LIMIT ? ";
    $param_values[] = filter_var($_GET['limit'], FILTER_SANITIZE_NUMBER_INT);
    $param_types[] = 'i';
}

// category
if (isset($_GET['category'])) {
    $sql .= " AND category = ? ";
    $param_values[] = filter_var($_GET['category'], FILTER_SANITIZE_STRING);
    $param_types[] = 's';
}

// tags
if (isset($_GET['tags'])) {
    $param_values[] = filter_var($_GET['category'], FILTER_SANITIZE_STRING);
    $param_types[] = 's';
}

// offset
if (isset($_GET['offset'])) {
    $sql .= " OFFSET ? ";
    $param_values[] = filter_var($_GET['offset'], FILTER_SANITIZE_NUMBER_INT);
    $param_types[] = 'i';
}

// date from
if (isset($_GET['date_from'])) {
    $param_values[] = filter_var($_GET['date_from'], FILTER_SANITIZE_STRING);
    $param_types[] = 's';
}

// date to
if (isset($_GET['date_to'])) {
    $param_values[] = filter_var($_GET['date_to'], FILTER_SANITIZE_STRING);
    $param_types[] = 's';
}

// month
if (isset($_GET['month'])) {
    $param_values[] = filter_var($_GET['month'], FILTER_SANITIZE_STRING);
    $param_types[] = 's';
}

echo $sql; exit;


