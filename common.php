<?php

// setup server and client error reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// establish database connection
$conn = mysql_connect("localhost", "root", "");
mysql_select_db("main", $conn);

?>