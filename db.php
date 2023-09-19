<?php

/* database connection information */
$db_server = 'localhost';
$db_user = 'sit103';
$db_password = 'P@55w0rd';
$db_name = 'sit103_miniprj';

$db_conn = new mysqli($db_server, $db_user, $db_password, $db_name);

if($db_conn->connect_error) {
    die('Database connection failure: ' . $db_conn->connect_error);
} else {
    // echo 'Database connection successful';
}

?>