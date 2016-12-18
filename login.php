<?php
require_once("utils.php");
require_once("checker.php");
require_once("DBHandler.php");
require_once("DHandler.php");
/* Handles all checks for user login. */

if (isset($_POST["username"]) && isset($_POST["password"]) &&
    !empty($_POST["username"]) && !empty($_POST["password"])) {
        /* Check that fields are filled and data is sent corretly. */
    if (recaptchaCheck()) { /* Calls Checker of recaptcha. */
        $dbh = DBHandlerClass::getInstance();

        $table = "users";
        $colums = array("uID", "uName", "pw_hash", "userMode");
        $addTerm = "WHERE uName = ?";
        $addAttr = array($_POST["username"]); /* Initialize parameters for database reader. */

        $userinfo = $dbh->readFromDB($table, $colums, $addTerm, $addAttr); /* Get asked users hash from dtabase. */

        if(isset($userinfo[0]) && pwVerify($_POST["password"], $userinfo[0]["pw_hash"])) {
            /* Checks that database have username which is trying to login.
            And calls pwVerify to verify password input. */

            $_SESSION["userID"] = $userinfo[0]["uID"]; /* If login is successful */
            $_SESSION["username"] = $userinfo[0]["uName"]; /* Store useful user info */
            $_SESSION["userMode"] = $userinfo[0]["userMode"]; /* to $_SESSION variables. */

            $userinfo = array();
            unset($_POST["username"]);
            unset($_POST["password"]);

            header("Location: http://www.ht.dev/index.php"); /* Redirect to front page. */

        } else { /* When ever login fails prints login form again and clear user arrays. */
            $userinfo = array();
            $_POST = array();
            loginForm();
        }

    } else {
        $userinfo = array();
        $_POST = array();
        loginForm();
    }
} else {
    infoText("Fill all the fields.");
    $_POST = array();
    loginForm();
}
