<?php
require_once("utils.php");
require_once("checker.php");
require_once("DBHandler.php");
require_once("DHandler.php");

if (isset($_POST["username"]) && isset($_POST["password"]) &&
    !empty($_POST["username"]) && !empty($_POST["password"])) {

    $dbh = DBHandlerClass::getInstance();

    $table = "users";
    $colums = array("uID", "uName", "pw_hash", "userMode");
    $addTerm = "WHERE uName = ?";
    $addAttr = array($_POST["username"]);

    $userinfo = $dbh->readFromDB($table, $colums, $addTerm, $addAttr);
    if (recaptchaCheck()) {
        if(isset($userinfo[0]) && pwVerify($_POST["password"], $userinfo[0]["pw_hash"])) {

            $_SESSION["userID"] = $userinfo[0]["uID"];
            $_SESSION["username"] = $userinfo[0]["uName"];
            $_SESSION["userMode"] = $userinfo[0]["userMode"];

            $userinfo = array();
            unset($_POST["username"]);
            unset($_POST["password"]);

    /*UNSET THINGS*/
            header("Location: http://www.ht.dev/index.php");
        }
    }
} else {
    $userinfo = array();
    /*UNSET THINGS*/
    loginForm();
}
