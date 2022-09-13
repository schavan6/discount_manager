<?php

//Get Heroku ClearDB connection information
//$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
//echo "db " . var_export($cleardb_url);
$cleardb_server = 'us-cdbr-east-06.cleardb.net';
$cleardb_username = 'b4eef75396cc08';
$cleardb_password = '887a5e18';
$cleardb_db = 'heroku_60b60956fe00952';
$active_group = 'default';
$query_builder = TRUE;
// Connect to DB
$link = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
 
// Check connection
if ($link === false) {
    #die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>