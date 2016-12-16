<?php
require_once("utils.php");
require_once("checker.php");
require_once("DBHandler.php");
require_once("DHandler.php");
/*phpinfo();*/

if (regCheck($_POST)) {
    validationForm();
     /* TÄSTÄ LÄHETETÄÄN CODE EMAIL VALIDATIONIIN JA USERNAME*/
     if (isset($_POST["username"])) {
         $_SESSION["username"] = $_POST["username"];
     }
 } else {
     regForm();
 }

if (isset($_POST["code"]) && isset($_SESSION["username"]) &&
    emailValidation($_POST["code"], $_SESSION["username"])) {

    $dbh = DBHandlerClass::getInstance();
    $table = "users";
    $colums = array("uName", "pw_hash", "salt", "rName", "email");

    $options = ["cost" => 11, "salt" =>
        bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM))];

    $memcache = $dbh->connectMemcache();
    $pw = password_hash($dbh->getMemcacheValue($_SESSION["username"]."_pw", $memcache), PASSWORD_BCRYPT, $options);

    $values = array(strip_tags($_SESSION["username"]), $pw, $options["salt"],
        strip_tags($dbh->getMemcacheValue($_SESSION["username"]."_rname", $memcache)),
        strip_tags($dbh->getMemcacheValue($_SESSION["username"]."_email", $memcache)));

    $dbh->writeToDB($table, $colums, $values);

    $db = null;
    unset($_POST["username"]);
    unset($_POST["code"]);
    unset($_POST["password"]);
    unset($_POST["email"]);
    unset($_POST["realname"]);

    infoText("Account created.");
    sleep(5);
    header("Location: http://www.ht.dev/index.php?p=login");
}

?>
