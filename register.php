<?php
require_once("utils.php");
require_once("checker.php");
require_once("DBHandler.php");
require_once("DHandler.php");
/* Handles all checks for user registeration. */

if (regCheck($_POST)) { /* Send $_POST variables to registeration checker. */
    validationForm(); /* If inputs are valid shows email validation form. */
     if (isset($_POST["username"]) && !empty($_POST["username"])) {
         $_SESSION["username"] = $_POST["username"];
     }
 } else {
     regForm();
 }

if (isset($_POST["code"]) && isset($_SESSION["username"]) &&
    emailValidation($_POST["code"], $_SESSION["username"])) {
        /* Email validation handler. */

    $dbh = DBHandlerClass::getInstance();
    $table = "users";
    $colums = array("uName", "pw_hash", "salt", "rName", "email");

    $options = ["cost" => 11, "salt" =>
        bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM))]; /* Generate salt for password. */

    $memcache = $dbh->connectMemcache(); /* Connect to memcache to get user inputs. */
    $pw = password_hash($dbh->getMemcacheValue($_SESSION["username"]."_pw", $memcache), PASSWORD_BCRYPT, $options);
    /* Generate bcrypt password with cost 11 and MCRYPT salt. */

    $values = array(strip_tags($_SESSION["username"]), $pw, $options["salt"],
        strip_tags($dbh->getMemcacheValue($_SESSION["username"]."_rname", $memcache)),
        strip_tags($dbh->getMemcacheValue($_SESSION["username"]."_email", $memcache)));

    $dbh->writeToDB($table, $colums, $values);
    /* Calls writer to rite user information to database. */

    unset($_POST["username"]); /* UNSET $_POST variables. */
    unset($_POST["code"]);
    unset($_POST["password"]);
    unset($_POST["email"]);
    unset($_POST["realname"]);

    infoText("Account created.");
    sleep(15);
    header("Location: http://www.ht.dev/index.php?p=login");
    /* Redirect to login page because user is not automatcally logged in.
    This will reduce spam accounts. */

}

?>
