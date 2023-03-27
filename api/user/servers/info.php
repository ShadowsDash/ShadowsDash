<?php
require("../../../require/config.php");
require("../../../require/sql.php");
require("../../../require/addons.php");
session_start();
header("Content-type: application/json"); // better readability

// GET PTERO SERVER INFO
$ch = curl_init($_CONFIG["ptero_url"] . "/api/application/servers/" . $ptid);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer " . $_CONFIG["ptero_apikey"],
    "Content-Type: application/json",
    "Accept: Application/vnd.pterodactyl.v1+json"
));
$result1 = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($httpcode != 200) {
    die(json_encode(array("error" => "Could not connect to the panel")));
}
curl_close($ch);
$result = json_decode($result1, true);

$result = $cpconn->query("SELECT * FROM servers WHERE id = '" . mysqli_real_escape_string($cpconn, $_GET['id']) . "'")->fetch_object();

$ch = curl_init($_CONFIG["ptero_url"] . "/api/application/servers/" . $result->pid);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer " . $_CONFIG["ptero_apikey"],
    "Content-Type: application/json",
    "Accept: Application/vnd.pterodactyl.v1+json"
));
$result1 = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$ptero = json_decode($result1, true);

echo json_encode(
    array(
        "db" => $result,
        "ptero" => $ptero['attributes']
        )
    );