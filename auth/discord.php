<?php
// ==============================================
// This script is 99.999% coming from J0SH#8445!
// Big shoutout and thx!
// ==============================================

session_start();
require("../require/sql.php");
require("../require/config.php");
require("../require/addons.php");

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';

if (!$cpconn->ping()) {
    $_SESSION['error'] = "There was an error communicating with MYSQL";
    header("location: /auth/login");
    die();
}
if (isset($_SESSION['loggedin'])) {
    if (isset($_SESSION["redirafterlogin"])) {
        header("location: " . $_SESSION["redirafterlogin"]);
    } else {
        header("location: /");
    }
    die();
}
if (isset($_GET['login'])) {
    try {
        $requestarray = array(
            "client_id" => $_CONFIG["dc_clientid"],
            "redirect_uri" => $_CONFIG["proto"] . $_SERVER['SERVER_NAME'] . "/auth/discord",
            "response_type" => "code",
            "scope" => "identify guilds email"
        );

        header("location: https://discord.com/api/oauth2/authorize?" . http_build_query($requestarray));
        die();
    }
    catch (exception $e) {
        $_SESSION['error'] = "There was an unknown error while redirecting to discord.";
        header("location: /auth/login");
        die();
    }
}
if (isset($_GET['code'])) {
    try {
        // Exchange the auth code for a token
        $token = apiRequest($tokenURL, array(
            "grant_type" => "authorization_code",
            'client_id' => $_CONFIG["dc_clientid"],
            'client_secret' => $_CONFIG["dc_clientsecret"],
            'redirect_uri' => $_CONFIG["proto"] . $_SERVER['SERVER_NAME'] . "/auth/discord",
            'code' => $_GET['code']
        ));
        $_SESSION['access_token'] = $token->access_token;
        header("location: /auth/discord");
        die();
    }
    catch (exception $e) {
        $_SESSION['error'] = "There was an unexpected error while fetching discord information.";
        header("location: /auth/login");
        die();

    }
}
if (isset($_SESSION['access_token'])) {
    $ipaddr = getclientip();
    $user = apiRequest($apiURLBase);
    $username = $user->username;
    $avatar = "https://cdn.discordapp.com/avatars/" . "$user->id" . '/' . "$user->avatar" . ".png";;
    if (empty($user->avatar)) {
        $avatar = "https://support.discord.com/hc/user_images/l12c7vKVRCd-XLIdDkLUDg.png";
    }
    $guilds = apiRequest($apiURLBase . "/guilds");
    // Check whether the user has removed scopes
    if (empty($user->email)) {
        $_SESSION['error'] = "Hey! We detected that you are editing the discord scopes!";
        header("location: /auth/login");
        die();
    }
    if (empty($guilds)) {
        $_SESSION['error'] = "Hey! We detected that you are editing the discord scopes!";
        header("location: /auth/login");
        die();
    }
    // Check if user is in the guild
    $inDiscord = false;
    foreach ($guilds as $guild) {
        if (!empty($guild->id)) {
            if ($guild->id == $_CONFIG["dc_guildid"]) {
                $inDiscord = true;
            }
        }
    }
    if ($inDiscord == false) {
        header("Location: /auth/errors/notondiscord");
        die();
    }
    $cpconn->query("INSERT INTO login_logs (ipaddr, userid) VALUES ('$ipaddr', '$user->id')");
    /*
    ALT DETECTOR
    */
    $userids = array();
    $loginlogs = mysqli_query($cpconn, "SELECT * FROM login_logs WHERE userid = '$user->id'");
    foreach ($loginlogs as $login) {
        $ip = $login['ipaddr'];
        if ($ip == "12.34.56.78") {
            continue;
        }
        $saio = mysqli_query($cpconn, "SELECT * FROM login_logs WHERE ipaddr = '$ip'");
        foreach ($saio as $hello) {
            if (in_array($hello['userid'], $userids)) {
                continue;
            }
            if ($hello['userid'] == $user->id) {
                continue;
            }
            array_push($userids, $hello['userid']);
        }
    }
    if (count($userids) !== 0) {
        if ($user->id != 638672769009319956 && $user->id != 536579437064486912) {
        $_SESSION["alts"] = $userids;
        header("location: /auth/errors/alting");
        die();
        }
    }
    /*
     * VPN & GEOIP Detector
     */
    if ($ipaddr == "127.0.0.1") {
        $ipaddr = "12.34.56.78";
    }
    $vpn = false;
    $response = file_get_contents("http://ip-api.com/json/" . $ipaddr . "?fields=status,message,country,regionName,city,timezone,isp,org,as,mobile,proxy,hosting,query");
    $response = json_decode($response, true);
    if (isset($response['proxy'])) {
        if ($response['proxy'] == true || $response['hosting'] == true) {
            $vpn = true;
        }
    }
    if ($response['type'] = !"Residential") {
        $vpn = true;
    }
    if ($ipaddr == "51.161.152.218" || $ipaddr == "66.220.20.165"){
        $vpn = false;
    }
    if ($vpn == true) {
        $_SESSION['error'] = "You are using a VPN. This is not allowed.";
        header("location: /auth/errors/vpn");
        die();
    }
    /*
    Check if user is already registered and if they aren't, create them a panel & client panel account
    */
    $usersignupcheck = mysqli_query($cpconn, "SELECT * FROM users WHERE discord_id = '" . mysqli_real_escape_string($cpconn, $user->id) . "'");
    if ($usersignupcheck->num_rows == 0) {
        $panel_username = file_get_contents($_CONFIG["proto"] . $_SERVER['SERVER_NAME'] . "/api/randompassword");
        $panel_password = file_get_contents($_CONFIG["proto"] . $_SERVER['SERVER_NAME'] . "/api/randompassword");
        $referral = file_get_contents($_CONFIG["proto"] . $_SERVER['SERVER_NAME'] . "/api/randompassword?length=5");

        $panelapi = curl_init($_CONFIG["ptero_url"] . "/api/application/users");
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $_CONFIG["ptero_apikey"]
        );
        $postfields = array(
            'username' => $panel_username,
            'first_name' => $user->username,
            'last_name' => $_CONFIG["name"],
            'email' => $user->email,
            'password' => $panel_password
        );
        curl_setopt($panelapi, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($panelapi, CURLOPT_POST, 1);
        curl_setopt($panelapi, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($panelapi, CURLOPT_POSTFIELDS, json_encode($postfields));
        $result = curl_exec($panelapi);
        curl_close($panelapi);
        $sasiuasdusad = $result;
        $result = json_decode($result, true);
        $panel_id = null;
        if (!isset($result['object'])) {
            $error = $result['errors'][0]['detail'];
            if ($error == "The email has already been taken.") {
                // retrieve user info and attach current user to client panel
                $ch = curl_init($_CONFIG["ptero_url"] . "/api/application/users?filter%5Bemail%5D=$user->email");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Bearer ' . $_CONFIG["ptero_apikey"],
                    'Content-Type: application/json',
                    'Accept: application/json'
                ));
                $result12 = curl_exec($ch);
                curl_close($ch);
                $result13 = json_decode($result12, true);
                if (!isset($result13['object'])) {
                    $_SESSION['error'] = "There was an unexpected error while attempting to link your panel account to the client portal.";
                    header("location: /auth/login");
                    die();
                }
                $panel_id = $result13['data'][0]['attributes']['id'];
                // update user information
                $ch = curl_init($_CONFIG["ptero_url"] . "/api/application/users/$panel_id");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Bearer ' . $_CONFIG["ptero_apikey"],
                    'Content-Type: application/json',
                    'Accept: application/json'
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
                    'username' => $panel_username,
                    'first_name' => $user->username,
                    'last_name' => $_CONFIG["name"],
                    'email' => $user->email,
                    'password' => $panel_password,
                    'language' => 'en'
                )));
                $updateUserResult = curl_exec($ch);
                curl_close($ch);
                $updateUserResult = json_decode($updateUserResult, true);
                if (!isset($updateUserResult['object'])) {
                    $_SESSION['error'] = "There was an error while updating your panel information on sign-up, your password might need resetting.";
                }
            } else {
                $_SESSION['error'] = "There was an error while signing up. Is our game panel down?";
                header("location: /auth/login");
                die();
            }
        
        } else {
            $panel_id = $result['attributes']['id'];
        }
        // Insert user into de big fat db

        $time = time();
        $registered = date("d-m-y", time());
        if (!mysqli_query($cpconn, "INSERT INTO users (panel_id, discord_id, discord_name, discord_email, avatar, panel_username, panel_password, register_ip, lastlogin_ip, created_at, last_login, locale, registered) VALUES ($panel_id, $user->id, '" . mysqli_real_escape_string($cpconn, $username) . "', '" . mysqli_real_escape_string($cpconn, $user->email) . "', '$avatar', '$panel_username', '$panel_password', '$ipaddr', '$ipaddr', '$time', '$time', 'en', '$registered')")) {
            $_SESSION['error'] = "There was an error while creating your user account. " . mysqli_error($cpconn);
            header("location: /auth/login");
            die();
        }
            if (!mysqli_query($cpconn, "INSERT INTO referral_codes (uid, referral) VALUES ('$user->id', '$referral')")) {
                $_SESSION['error'] = "There was an error creating you a referral code.";
                header("location: /auth/login");
                die();
            }
            /*
             * Referrals
             */
            if (isset($_SESSION['referral'])) {
                $r = mysqli_query($cpconn, "SELECT * FROM referral_codes WHERE referral = '" . mysqli_real_escape_string($cpconn, $_SESSION['referral']) . "'")->fetch_object();
                $referrer = mysqli_query($cpconn, "SELECT * FROM users WHERE discord_id = '$r->uid'")->fetch_object();
                $newc = $referrer->coins + 30;
                mysqli_query($cpconn, "UPDATE users SET coins = '$newc' WHERE discord_id = '$r->uid'");
                $time = time();
                mysqli_query($cpconn, "INSERT INTO referral_claims (`code`, `uid`, `timestamp`) VALUES ('$r->referral', '$user->id', '$time')");
                mysqli_query($cpconn, "UPDATE users SET coins = '50' WHERE discord_id = '$user->id'");
                $_SESSION['success'] = "You used a referral from " . $referrer->discord_name . ", so you just earned 50 coins.";
            }

            $_SESSION['firstlogin'] = true;
        } else {
            $userdb = $cpconn->query("SELECT * FROM users WHERE discord_id = '" . mysqli_real_escape_string($cpconn, $user->id) . "'")->fetch_all(MYSQLI_ASSOC);
            $time = time();
            mysqli_query($cpconn, "UPDATE users SET avatar = '$avatar' WHERE discord_id = '$user->id'");
            mysqli_query($cpconn, "UPDATE users SET discord_name = '" . mysqli_real_escape_string($cpconn, $username) . "' WHERE discord_id = '$user->id'");
            mysqli_query($cpconn, "UPDATE users SET discord_email = '$user->email' WHERE discord_id = '$user->id'");
            mysqli_query($cpconn, "UPDATE users SET last_login = '$time' WHERE discord_id = '$user->id'");
            mysqli_query($cpconn, "UPDATE users SET lastlogin_ip = '$ipaddr' WHERE discord_id = '$user->id'");
            if ($userdb[0]["banned"] == 1) {
                $_SESSION['ban_reason'] = $userdb[0]["banned_reason"];
                session_destroy();
                header("location: /auth/errors/banned");
                die();
            }
            $_SESSION['firstlogin'] = false;
        }
        // /*
        //  * Join for resources
        //  */
        $jfr = $cpconn->query("SELECT * FROM j4r WHERE status = 'APPROVED'");
        $jfrclaimed = $cpconn->query("SELECT * FROM j4r_claimed WHERE userid = '" . $user->id . "'");
        $checked = array();
        $alrClaimed = array();
        $userdb = $cpconn->query("SELECT * FROM users WHERE discord_id = '" . mysqli_real_escape_string($cpconn, $user->id) . "'")->fetch_all(MYSQLI_ASSOC);
        foreach ($jfrclaimed as $cjfr) {
            array_push($alrClaimed, $cjfr["serverid"]);
        }
        foreach ($jfr as $server) {
            foreach ($guilds as $guild) {
                if (!empty($guild->id)) {
                    array_push($checked, $guild->id);
                    if (in_array($guild->id, $alrClaimed)) {
                        // user already got resources
                        continue;
                    }
                    if ($guild->id == $server["serverid"]) {
                        // ADD QC
                        $currentcoins = $userdb[0]["coins"];
                        $newcoins = $currentcoins + $server["qc"];
                        $cpconn->query("UPDATE `users` SET `coins` = '$newcoins' WHERE `users`.`discord_id` = '" . $user->id . "'");
                        // add 1 join from j4r db
                        $cpconn->query("UPDATE `j4r` SET `joins` = " . $server["joins"] + 1 . " WHERE `j4r`.`id` = " . $server["id"]);
                        // add to j4r_claimed
                        $cpconn->query("INSERT INTO `j4r_claimed` (`id`, `serverid`, `userid`) VALUES (NULL, '" . $server["serverid"] . "', '" . $user->id . "')");
                    }
                }
            }
        }

        foreach ($jfrclaimed as $cjfr) {
            if (!in_array($cjfr["serverid"], $checked)) {
               // REMOVE QC (USER LEFT JFR)
                $userdb = $cpconn->query("SELECT * FROM users WHERE discord_id = '" . mysqli_real_escape_string($cpconn, $user->id) . "'")->fetch_all(MYSQLI_ASSOC);
                $curjfr = $cpconn->query("SELECT * FROM j4r WHERE serverid = '" . mysqli_real_escape_string($cpconn, $cjfr["serverid"]) . "'")->fetch_all(MYSQLI_ASSOC);
                $currentcoins = $userdb[0]["coins"];
                $newcoins = $currentcoins - $curjfr[0]["qc"];
                $cpconn->query("UPDATE `users` SET `coins` = '$newcoins' WHERE `users`.`discord_id` = '" . $user->id . "'");
                // remove 1 join from j4r db
                $cpconn->query("UPDATE `j4r` SET `joins` = '" . $curjfr[0]["joins"] - 1 . "' WHERE `j4r`.`id` = '" . $curjfr[0]["id"] . "'");
                // remove from j4r_claimed
                $cpconn->query("DELETE FROM `j4r_claimed` WHERE `j4r_claimed`.`id` = '" . $cjfr["id"] . "'");
            
        }
    }

        $_SESSION['user'] = $user;
        $_SESSION["uid"] = $user->id;
        $_SESSION['loggedin'] = true;
        if ($_SESSION['firstlogin'] == true) {
            header("location: welcome");
            logClient("[Auth] :wave: <@" . $user->id . "> logged in for the first time!");

            $url = "changeme";
            $headers = [ 'Content-Type: application/json; charset=utf-8' ];
            $message = ":wave: <@" . $user->id . "> logged in for the first time!";
            $POST = [ 'username' => 'New users', 'content' => $message ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
            $response   = curl_exec($ch);
        } else {
            if (isset($_SESSION["redirafterlogin"])) {
                header("location: " . $_SESSION["redirafterlogin"]);
            } else {
                header("location: /");
            }
            logClient("[Auth] <@" . $user->id . "> logged in.");
        }


    } else {
        header("location: ?login");
        die();
    }
    function apiRequest($url, $post = FALSE, $headers = array())
    {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            $response = curl_exec($ch);


            if ($post)
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

            $headers[] = 'Accept: application/json';
            if ($_SESSION['access_token'])
                $headers[] = 'Authorization: Bearer ' . $_SESSION['access_token'];


            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            return json_decode($response);
        } catch (exception $e) {

        }
    }
