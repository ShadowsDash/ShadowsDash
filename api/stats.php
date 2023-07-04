<?php
require("../require/sql.php");
header("Content-type: application/json");
$users = $cpconn->query("SELECT * FROM users");
$servers = $cpconn->query("SELECT * FROM servers");
http_response_code(200);
die(json_encode(array("users"=>$users->num_rows,"servers"=>$servers->num_rows)))
?>