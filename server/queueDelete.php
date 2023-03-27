<?php
require("../require/config.php");
require("../require/sql.php");
require("../require/addons.php");
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("location: /login");
    die();
}
if (!is_numeric($_GET["server"])) {
    $_SESSION['error'] = "Server id is invalid.";
    header("location: /");
    die();
}
/*
 * Check user owns server
 */
$ownsServer = mysqli_query($cpconn, "SELECT * FROM servers_queue WHERE id = '" . mysqli_real_escape_string($cpconn, $_GET["server"]) . "'");
if ($ownsServer->num_rows == 0) {
    $_SESSION['error'] = "You don't have permission to delete this server or it doesn't exist.";
    header("location: /");
    die();
}
/*
 * Delete server
*/
$serverInformation = $ownsServer->fetch_object();
if ($serverInformation->type == 2) {
    $userdb = mysqli_query($cpconn, "SELECT * FROM users WHERE discord_id = '" . mysqli_real_escape_string($cpconn, $user->id) . "'")->fetch_object();
    $current_qc = $userdb->coins;
    $new_qc = $_CONFIG["vipqueue"] + $current_qc;
    mysqli_query($cpconn, "UPDATE users SET coins = '$new_qc' WHERE discord_id = '" . mysqli_real_escape_string($cpconn, $user->id) . "'");
}
if (mysqli_query($cpconn, "DELETE FROM servers_queue WHERE id = '" . mysqli_real_escape_string($cpconn, $_GET["server"]) . "'")) {
    header("location: /");
    $_SESSION['success'] = "Your server is no longer in queue!";
    logClient("[Server queue deletion] <@" . $user->id . "> removed the server from queue with ID #" . $_GET["server"] . ".");
    die();
}
else {
    $_SESSION['error'] = "Hmmm. Cannot delete your server from the queue, contact staff.";
    header("location: /");
    die();
}