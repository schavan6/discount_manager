<?php

//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
echo "db " . var_export($cleardb_url);
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;
// Connect to DB
$link = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
 
// Check connection
if ($link === false) {
    #die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>