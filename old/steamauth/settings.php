<?php
$steamauth['apikey'] = "270DE7B9FAD5C17DEEA03B022284DCFF"; // Your Steam WebAPI-Key found at http://steamcommunity.com/dev/apikey
$steamauth['domainname'] = "beta.browser.tf"; // The main URL of your website displayed in the login page
$steamauth['buttonstyle'] = "large_no"; // Style of the login button [small|large_no|large]
$steamauth['logoutpage'] = "../index.php"; // Page to redirect to after a successfull logout (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!
$steamauth['loginpage'] = "../index.php"; // Page to redirect to after a successfull login (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!

// System stuff
if (empty($steamauth['apikey'])) {die("<div style='display: block; width: 100%; background-color: red; text-align: center;'>SteamAuth:<br>Please supply an API-Key!</div>");}
if (empty($steamauth['domainname'])) {$steamauth['domainname'] = "localhost";}
if ($steamauth['buttonstyle'] != "small" and $steamauth['buttonstyle'] != "large") {$steamauth['buttonstyle'] = "large_no";}
?>