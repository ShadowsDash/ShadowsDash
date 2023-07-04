<?php
/**
 * @var array $_CONFIG
 */
# PLEASE READ ALL THE COMMENTS TO HELP YOU LEARN HOW TO CONFIGURE SHADOW'S DASH.
# NO HELP WILL BE PROVIDED IF THE QUESTION CAN BE ANSWERED IN THIS CONFIG FILE.
# ============================================
# Thanks for installing Shadow's Dash!
# This is your configuration file. You can learn
# more about what you can do in the documentation.
# 
# This file is included in 90% of the pages. You can access them using the "$_CONFIG" variable. 
#
# <!> This is not the place to edit eggs or nodes.
# There should be a table for the respective features.
#
$_CONFIG["name"] = "Shadow's Dash"; // Name of your host
$_CONFIG["logo_white"] = "https://iili.io/VWYkua.md.png"; // White version of your text logo (Image URL)
$_CONFIG["logo_black"] = "https://iili.io/VWYkua.md.png"; // Black version of your text logo (Image URL)
$_CONFIG["website"] = ""; // Main website link, not client
$_CONFIG["statuspage"] = ""; // Status page link
$_CONFIG["discordserver"] = ""; // Discord server invite link
$_CONFIG["privacypolicy"] = ""; // Privacy policy - If you want to start an host, please do this or you'll get drama.gg'ed ;)
$_CONFIG["termsofservice"] = ""; // Terms of service - NOT RULES! :) - If you want to start an host, please do this or you'll get drama.gg'ed ;)
$_CONFIG["home_background"] = "https://i.imgur.com/ksvpSN3.jpeg"; // The background of the home page
$_CONFIG["home_color"] = "warning"; // The card colors of the home page
$_CONFIG["favicon"] = "https://iili.io/VazMs2.png"; // A .png image link for your favicon.

// >> HOME NEWS
// The news showing next to the "Hello username#tag!" in the home page.
// Exemple: https://i.imgur.com/7a8QR5c.png
$_CONFIG["homeNews_show"] = false;
$_CONFIG["homeNews_title"] = "";
$_CONFIG["homeNews_content"] = "";
$_CONFIG["homeNews_bgimage"] = ""; // Leave empty for none | we recommend a darken background image, for better text reading on light images
$_CONFIG["homeNews_bgcolor"] = ""; // Leave empty for the default color
$_CONFIG["homeNews_buttonLink"] = "";
$_CONFIG["homeNews_buttonText"] = "";

$_CONFIG["vipqueue"] = "30"; // price of the vip queue

// >> LOGIN QUEUE
// The login cooldown. If the cooldown is for exemple 30 seconds, only one user per 30 seconds can login.
// Others will see a page indicating that they need to wait to login. /!\ Longer cooldown times = longer wait and more people in queue!
// Set this to 0 to disable the cooldown. (If you change this live, it will take effect on the next user leaving the queue)
$_CONFIG["loginCooldown"] = 0;


//
// >> WEB CONFIGURATIONS
//
$_CONFIG["proto"] = "https://"; // protocol for the client area. Must be http or https with the :// at the end.
$_CONFIG["ptero_url"] = ""; // the url to your pterodactyl web server. This will be used for API.
$_CONFIG["ptero_apikey"] = ""; // [!] Must be an application api key with all rights.

//
// >> DATABASE AND API KEYS RELATED STUFF, CONFIDENTIAL
//
$_CONFIG["db_host"] = "";
$_CONFIG["db_port"] = "3306";
$_CONFIG["db_name"] = "";
$_CONFIG["db_username"] = "";
$_CONFIG["db_password"] = "";

// >>> Discord
$_CONFIG["dc_clientid"] = ""; // The client ID of the Discord oAuth application
$_CONFIG["dc_clientsecret"] = ""; // The client secret of the Discord oAuth application
$_CONFIG["dc_guildid"] = ""; // Your Discord guild ID
// To configure the log webhook, edit the url in "addons.php", var "$url" in the logClient() function.
// To configure the QUEUE log webhook, edit the url in "scripts/queueHandler.php", var "$url" in the logQueue() function.

// >> EARNING METHODS
// Each line is a new earning method showing into the "Select a method to earn coins" screen.
// Template: $_CONFIG["earningMethods"][x] = array("icon" => "", "name" => "", "link" => "");
// add the index between the brackets to change the order of the earning methods. (0 = first, 1 = second, etc.)