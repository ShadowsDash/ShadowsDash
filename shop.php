<?php
require("require/page.php");
$userdb = mysqli_query($cpconn, "SELECT * FROM users where discord_id = '". $_SESSION["user"]->id. "'")->fetch_object();

// CPU LIMIT
if (isset($_POST["cpulimit"])) {
    $amount = floor($_POST["amount"]);
    $price = ($amount/50)*750;
    if (empty($amount)) {
        die("You need to specify a correct amount");
    }
    if ($amount < 0) {
        die("You need to specify a correct amount");
    }
    if ($userdb->coins < $price) {
        $_SESSION["error"] = "You do not have enough coins!";
        echo '<script>window.location.replace("/");</script>';
        die();
    }
    mysqli_query($cpconn, "UPDATE `users` SET `cpu` = '" . ($userdb->cpu + $amount) . "' WHERE `users`.`discord_id` = " . $_SESSION["user"]->id); // ADD
    mysqli_query($cpconn, "UPDATE `users` SET `coins` = '" . ($userdb->coins - $price) . "' WHERE `users`.`discord_id` = " . $_SESSION["user"]->id); // PAY
    $_SESSION["success"] = "You successfully bought $amount% of CPU!";
    logClient("<@" . $_SESSION["user"]->id . "> bought **$amount% of CPU**!");
    echo '<script>window.location.replace("/");</script>';
    die();
}

// RAM LIMIT
if (isset($_POST["ramlimit"])) {
    $amount = floor($_POST["amount"]);
    $price = ($amount/1024)*350;
    if (empty($amount)) {
        die("You need to specify a correct amount");
    }
    if ($amount < 0) {
        die("You need to specify a correct amount");
    }
    if ($userdb->coins < $price) {
        $_SESSION["error"] = "You do not have enough coins!";
        echo '<script>window.location.replace("/");</script>';
        die();
    }
    mysqli_query($cpconn, "UPDATE `users` SET `memory` = '" . ($userdb->memory + $amount) . "' WHERE `users`.`discord_id` = " . $_SESSION["user"]->id); // ADD
    mysqli_query($cpconn, "UPDATE `users` SET `coins` = '" . ($userdb->coins - $price) . "' WHERE `users`.`discord_id` = " . $_SESSION["user"]->id); // PAY
    $_SESSION["success"] = "You successfully bought $amount MB of RAM!";
    logClient("<@" . $_SESSION["user"]->id . "> bought **$amount MB of RAM**!");
    echo '<script>window.location.replace("/");</script>';
    die();
}
// DISK LIMIT
if (isset($_POST["diskspace"])) {
    $amount = floor($_POST["amount"]);
    $price = ($amount/1024)*350;
    if (empty($amount)) {
        die("You need to specify a correct amount");
    }
    if ($amount < 0) {
        die("You need to specify a correct amount");
    }
    if ($userdb->coins < $price) {
        $_SESSION["error"] = "You do not have enough coins!";
        echo '<script>window.location.replace("/");</script>';
        die();
    }
    mysqli_query($cpconn, "UPDATE `users` SET `disk_space` = '" . ($userdb->disk_space + $amount) . "' WHERE `users`.`discord_id` = " . $_SESSION["user"]->id); // ADD
    mysqli_query($cpconn, "UPDATE `users` SET `coins` = '" . ($userdb->coins - $price) . "' WHERE `users`.`discord_id` = " . $_SESSION["user"]->id); // PAY
    $_SESSION["success"] = "You successfully bought $amount MB of disk!";
    logClient("<@" . $_SESSION["user"]->id . "> bought **$amount MB of Disk**!");
    echo '<script>window.location.replace("/");</script>';
    die();
}
// SERVER SLOTS
if (isset($_POST["serverslots"])) {
    $amount = floor($_POST["amount"]); // using floor to prevent decimals
    $price = $amount*250;
    if (empty($amount)) {
        die("You need to specify a correct amount");
    }
    if ($amount < 0) {
        die("You need to specify a correct amount");
    }
    if ($userdb->coins < $price) {
        $_SESSION["error"] = "You do not have enough coins!";
        echo '<script>window.location.replace("/");</script>';
        die();
    }
    mysqli_query($cpconn, "UPDATE `users` SET `server_limit` = '" . ($userdb->server_limit + $amount) . "' WHERE `users`.`discord_id` = " . $_SESSION["user"]->id); // ADD
    mysqli_query($cpconn, "UPDATE `users` SET `coins` = '" . ($userdb->coins - $price) . "' WHERE `users`.`discord_id` = " . $_SESSION["user"]->id); // PAY
    $_SESSION["success"] = "You successfully bought $amount server slot(s)!";
    logClient("<@" . $_SESSION["user"]->id . "> bought **$amount server slots**!");
    echo '<script>window.location.replace("/");</script>';
    die();
}
// HIBERNATE BYPASS
if (isset($_POST["hibernatebypass"])) {
    $pid = $_POST["serverid"];
    if (empty($pid)) {
        die("Empty server id.");
    }
    $price = 75;
    if ($userdb->coins < $price) {
        $_SESSION["error"] = "You do not have enough coins!";
        echo '<script>window.location.replace("/");</script>';
        die();
    }
    $ch = curl_init($_CONFIG["ptero_url"] . "/api/application/servers/" . $pid);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer " . $_CONFIG["ptero_apikey"],
        "Content-Type: application/json",
        "Accept: Application/vnd.pterodactyl.v1+json"
    ));
    $result = json_decode(curl_exec($ch));
    mysqli_query($cpconn, "INSERT INTO `hibernate_whitelist` (`id`, `uid`) VALUES (NULL, '"  . $result->attributes->uuid . "') ");
    mysqli_query($cpconn, "UPDATE `users` SET `coins` = '" . ($userdb->coins - $price) . "' WHERE `users`.`discord_id` = " . $_SESSION["user"]->id);
    $_SESSION["success"] = "Hibernate should now be disabled on that server. Remove 'hibernate.jar' from the plugins folder and restart your server!";
    logClient("<@" . $_SESSION["user"]->id . "> bought **1 hibernate bypass**!");
    echo '<script>window.location.replace("/");</script>';
    die();
}
// VIPQUEUE
if (isset($_POST["vipqueue"])) {
    $id = $_POST["serverid"];
    if (empty($id)) {
        die("Empty server id.");
    }
    echo '<script>window.location.replace("/server/buyVip?server=' . $id . '");</script>';
    die();
}

?>
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Coins shop</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Coins shop</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<input id="node" name="node" type="hidden" value="">
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row justify-content-center">
    <div class="col-md-12">
            </div>
        <div class="col-lg-8 card-wrapper">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0"><img src="https://i.imgur.com/2WYzXDV.png" width="30"> Coins shop</h3>
                </div>
                <div class="card-body" style="text-align: center;">
                    <p>Resources</p>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CPULimitModal" style="margin-bottom: 10px; margin-right: 10px;"><img src="https://i.imgur.com/b6TNCeZ.png" width="64"><br/><br/>Buy more cpu</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#RAMLimitModal" style="margin-bottom: 10px; margin-right: 10px;"><img src="https://i.imgur.com/sxZ4OB4.png" width="64"><br/><br/>Buy more ram</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#diskSpaceModal" style="margin-bottom: 10px; margin-right: 10px;"><img src="https://i.imgur.com/N0MwF0M.png" width="64"><br/><br/>Buy more disk</button>
                    <div class="col-md-12">
            <br><br>
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8751501047173409" crossorigin="anonymous"></script>
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-8751501047173409" data-ad-slot="5459512561" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            <br><br>
        </div>
                    <p>Account related</p>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#serverSlotsModal" style="margin-bottom: 10px; margin-right: 10px;"><img src="https://i.imgur.com/3w5wt0k.png" width="64"><br/><br/>Buy more server slots</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#hibernatePassModal" style="margin-bottom: 10px; margin-right: 10px;"><img src="https://i.imgur.com/K6S8u5h.png" width="64"><br/><br/>Buy 1 hibernation bypass</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#vipPassModal" style="margin-bottom: 10px; margin-right: 10px;"><img src="https://i.imgur.com/cIgYy4G.png" width="64"><br/><br/>Buy 1 VIP queue pass</button>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <br><br>
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8751501047173409" crossorigin="anonymous"></script>
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-8751501047173409" data-ad-slot="8886130990" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            <br><br>
        </div>
    </div>
    <!-- Modals for shop -->

    <!--
        CPU limit   
    -->
    <div class="modal fade" id="CPULimitModal" tabindex="-1" role="dialog" aria-labelledby="CPULimitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CPULimitModalLabel">Buy CPU</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>Move the slider to select the amount of CPU you want to purchase.</b>
                        <br/>
                        <p>Cost: <b>750 coins <small style="font-size: 10px;">&nbsp;/50%</small></b></p>    
                        </p>
                        <input type="range" class="form-control" min="50" max="300" value="100" class="slider-color" step="50" name="amount" oninput="CPURangeHandler(this.value)" onchange="CPURangeHandler(this.value)">
                        <p>
                            Selected amount: <b><span id="CPUselectedamount">50</span>%</b><br/>
                            Cost: <b><span id="CPUcost">750</span> coins.</b>
                        </p>
                        <script>
                            function CPURangeHandler(value) {
                                document.getElementById("CPUselectedamount").innerHTML = value;
                                document.getElementById("CPUcost").innerHTML = (value/100)*750;
                            }
                        </script>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="cpulimit" class="btn btn-primary">Buy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--
        RAM   
    -->
    <div class="modal fade" id="RAMLimitModal" tabindex="-1" role="dialog" aria-labelledby="RAMLimitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="RAMLimitModalLabel">Buy RAM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>Move the slider to select the amount of RAM you want to purchase.</b>
                        <br/>
                        <p>Cost: <b>350 coins <small style="font-size: 10px;">&nbsp;/1024MB</small></b></p>    
                        </p>
                        <input type="range" class="form-control" min="1024" max="10240" value="1024" class="slider-color" step="1024" name="amount" oninput="RAMRangeHandler(this.value)" onchange="RAMRangeHandler(this.value)">
                        <p>
                            Selected amount: <b><span id="RAMselectedamount">1024</span>MB</b><br/>
                            Cost: <b><span id="RAMcost">350</span> coins.</b>
                        </p>
                        <script>
                            function RAMRangeHandler(value) {
                                document.getElementById("RAMselectedamount").innerHTML = value;
                                document.getElementById("RAMcost").innerHTML = (value/1024)*350;
                            }
                        </script>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="ramlimit" class="btn btn-primary">Buy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--
        Disk space   
    -->
    <div class="modal fade" id="diskSpaceModal" tabindex="-1" role="dialog" aria-labelledby="diskSpaceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="diskSpaceModalLabel">Buy disk space</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>Move the slider to select the amount of disk you want to purchase.</b>
                        <br/>
                        <p>Cost: <b>350 coins <small style="font-size: 10px;">&nbsp;/1024MB</small></b></p>    
                        </p>
                        <input type="range" class="form-control" min="1024" max="10240" value="1024" class="slider-color" step="1024" name="amount" oninput="diskRangeHandler(this.value)" onchange="diskRangeHandler(this.value)">
                        <p>
                            Selected amount: <b><span id="diskselectedamount">1024</span>MB</b><br/>
                            Cost: <b><span id="diskcost">350</span> coins.</b>
                        </p>
                        <script>
                            function diskRangeHandler(value) {
                                document.getElementById("diskselectedamount").innerHTML = value;
                                document.getElementById("diskcost").innerHTML = (value/1024)*350;
                            }
                        </script>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="diskspace" class="btn btn-primary">Buy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--
        Server slots    
    -->
    <div class="modal fade" id="serverSlotsModal" tabindex="-1" role="dialog" aria-labelledby="serverSlotsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serverSlotsModalLabel">Buy a server slot</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>Input the amount of server slots you wish to buy:</b>
                        <br/>
                        <p>Cost: <b>250 coins <small style="font-size: 10px;">&nbsp;/slot</small></b></p>    
                        </p>
                        <input type="text" class="form-control" name="amount" placeholder="1" value="1" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="serverslots" class="btn btn-primary">Buy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--
        HIBERNATE PASS
    -->
    <div class="modal fade" id="hibernatePassModal" tabindex="-1" role="dialog" aria-labelledby="hibernatePassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hibernatePassModalLabel">Buy one server hibernation pass</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                    <p>Select a server to remove the hibernation mode from it. Once bought, this will be applied on the next server reboot.
                        <br/>
                        <p>Cost: <b>75 coins <small style="font-size: 10px;">&nbsp;/server</small></b></p>
                    </p>
                    <select class="form-control" name="serverid">
                        <?php
                        $servers = mysqli_query($cpconn, "SELECT * FROM servers WHERE uid = '" . mysqli_real_escape_string($cpconn, $_SESSION["user"]->id) . "'");
                        if ($servers->num_rows == 0) {
                            echo '<option disabled selected>🛑 You have no servers!</option>';
                        }
                        foreach ($servers as $server) {
                            // GET SERVER INFO
                            $location = mysqli_query($cpconn, "SELECT * FROM locations WHERE `locations`.`id`='" . $server["location"] . "'")->fetch_array();
                            $ch = curl_init($_CONFIG["ptero_url"] . "/api/application/servers/" . $server["pid"]);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                "Authorization: Bearer " . $_CONFIG["ptero_apikey"],
                                "Content-Type: application/json",
                                "Accept: Application/vnd.pterodactyl.v1+json"
                            ));
                            $result = json_decode(curl_exec($ch),true);
                            $hibwhitelist = file_get_contents("https://" . $_SERVER["SERVER_NAME"] . "/api/user/hibwhitelist?serverid=" . $result["attributes"]["uuid"]);
                            if ($hibwhitelist == 0) {
                                echo '<option value="' . $server["pid"] . '">' . $result["attributes"]["name"] . ' - ' . $location["name"] . '</option>';
                            } else {
                                echo '<option disabled>🛑 Hibernate is already disabled! - ' . $result["attributes"]["name"] . ' - ' . $location["name"] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <?php
                    if ($servers->num_rows == 0) {
                        echo '<button type="button" class="btn btn-danger">You have no servers!</button>';
                    } else {
                        echo '<button type="submit" name="hibernatebypass" class="btn btn-primary">Buy</button>';
                    }
                    ?>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- 
        VIP PASS
    -->
    <div class="modal fade" id="vipPassModal" tabindex="-1" role="dialog" aria-labelledby="vipPassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vipPassModalLabel">Buy a VIP pass</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>Select a server to move to the VIP queue. Make sure you select the correct server! <b>You will not get refund if you select the wrong server!</b></p>
                        <select class="form-control" name="serverid">
                            <?php
                            $servers_in_queue = mysqli_query($cpconn, "SELECT * FROM servers_queue WHERE ownerid = '" . mysqli_real_escape_string($cpconn, $_SESSION["user"]->id) . "'");
                            if ($servers_in_queue->num_rows == 0) {
                                echo '<option disabled selected>🛑 You have no servers in queue!</option>';
                            }
                            foreach ($servers_in_queue as $server) {
                                if ($server["type"] == 1) {
                                    echo '<option value="' . $server["id"] . '">' . $server["name"] . '</option>';
                                }
                                
                            }
                            ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <?php
                        if ($servers_in_queue->num_rows == 0) {
                            echo '<button type="button" class="btn btn-danger">You have no servers in queue!</button>';
                        } else {
                            echo '<button type="submit" name="vipqueue" class="btn btn-primary">Buy</button>';
                        }
                        ?>

                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6">
                <div class="copyright text-center  text-lg-left  text-muted">
                    &copy; 2021 <a href="https://xshadow.me" class="font-weight-bold ml-1" target="_blank">X_Shadow_#5962</a> - Theme by <a href="https://creativetim.com" target="_blank">Creative Tim</a>
                </div>
            </div>
            <div class="col-lg-6">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                    <li class="nav-item">
                        <a href="<?= $_CONFIG["website"] ?>" class="nav-link" target="_blank"> Website</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $_CONFIG["statuspage"] ?>" class="nav-link" target="_blank">Uptime / Status</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $_CONFIG["privacypolicy"] ?>" class="nav-link" target="_blank">Privacy policy</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $_CONFIG["termsofservice"] ?>" class="nav-link" target="_blank">Terms of service</a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    </div>
    </div>
    <script src="/assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/vendor/js-cookie/js.cookie.js"></script>
    <script src="/assets/vendor/nouislider/js/nouislider.min.js"></script>
    <script src="/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
    <!-- Optional JS -->
    <script src="/assets/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/vendor/bootstrap-notify/bootstrap-notify.min.js"></script>
    <!-- Argon JS -->
    <script src="/assets/js/argon.js?v=1.2.0"></script>
    <!-- Demo JS - remove this in your project -->
    <script src="/assets/js/demo.min.js"></script>
</div>

</html>
