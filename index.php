<?php
require_once ("utils.php");

HTMLheader();
advertisement();
HTMLnavigation();

if (isset($_SESSION['username']) || (isset($_GET["p"]) && $_GET["p"] === "register")) {
    if (isset($_GET["p"]) && $_GET["p"] === "login") {
        require("login.php");

    } else if (isset($_GET["p"]) && $_GET["p"] === "logout") {
        require("logout.php");

    } else if (isset($_GET["p"]) && $_GET["p"] === "register") {
        require("register.php");

    } else if (isset($_GET["p"]) && $_GET["p"] === "forums") {
        require("forums.php");

    } else if (isset($_GET["p"]) && $_GET["p"] === "readTopic") {
        require("topic.php");
    }

} else {
    require("login.php");
}

HTMLfooter();
?>
