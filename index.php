<?php
require_once ("utils.php");
/* Main page which handels which site is requested. Uses front controller idea.
If user is not logged in or in registeration page. User is redirected to login page. */

HTMLheader(); /* Main header of page */
advertisement(); /* Canvas add. Currently have just LUT image with link to www.lut.fi */
HTMLnavigation(); /* Navigation panel */

if ((isset($_SESSION['username']) && !empty($_SESSION['username'])) ||
    ((isset($_GET["p"]) && $_GET["p"] === "register"))) {

    if (isset($_GET["p"]) && $_GET["p"] === "login") { /* Login page */
        require("login.php");

    } else if (isset($_GET["p"]) && $_GET["p"] === "logout") { /* Logout page */
        require("logout.php");

    } else if (isset($_GET["p"]) && $_GET["p"] === "register") { /* Registeration page */
        require("register.php");

    } else if (isset($_GET["p"]) && $_GET["p"] === "forums") { /* Page with lists of all topics */
        require("forums.php");

    } else if (isset($_GET["topic"])) { /* Topic page when user goes to page of any topic. */
        $_SESSION['topicName'] = $_GET["topic"];
        require("topic.php");
    }

} else { /* In every other situation redirecting to the login page */
    require("login.php");
}

HTMLfooter(); /* Footer of page */
?>
