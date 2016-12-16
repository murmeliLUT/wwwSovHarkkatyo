<?php
require_once "utils.php";
require_once "DBHandler.php";
require_once "DHandler.php";
require_once "emailSender.php";

function regCheck(array $post) {
    if(isset($post["username"]) && !empty($post["username"]) &&
        isset($post["password"]) && isset($post["password_again"]) &&
        isset($post["realname"]) && !empty($post["realname"]) &&
        isset($post["email"]) && !empty($post["email"])) {
        if (usernameCheck($post["username"])) {
            if (pwCheck($post["password"], $post["password_again"])) {
                if (checkDupsFromDB($post["username"], $post["email"])) {
                    if (emailCheck($post["email"])) {
                        if (recaptchaCheck($post)) {
                            $dbh = DBHandlerClass::getInstance();
                            $memcache = $dbh->connectMemcache();
                            if(!($dbh->getMemcacheValue($post['username'], $memcache))
                            && !($dbh->getMemcacheValue($post['email'], $memcache))) {

                                $dbh->setMemcacheValue($post['username'], $memcache, $post["username"]);
                                $dbh->setMemcacheValue($post['username']."_pw", $memcache, $post["password"]);
                                $dbh->setMemcacheValue($post['username']."_email", $memcache, $post["email"]);
                                $dbh->setMemcacheValue($post['username']."_rname", $memcache, $post["realname"]);

                                $code = generateValue(20);
                                $dbh->setMemcacheValue($post['username']."_code", $memcache, $code);
                                send_e_mail($post["email"], "Your validation key: " . $code, "Validation code");

                                return 1;
                            }
                        }
                    }
                }
            }
            return 0;
        }
    }
    infoText("Required fields: username, password, password again, email, real name.");
    return 0;
}

function pwCheck($pw1, $pw2) {
    if($pw1 === $pw2 && !empty($pw1)) {

        if (strlen($pw1) > 8 && strlen($pw1) < 256) {

            if (preg_match('/[a-zåäö]/', $pw1) && preg_match('/[A-ZÅÄÖ]/', $pw1)
              && preg_match('/[0-9]/', $pw1)) {

                  return 1;

             } else {
              infoText("Password must contain at least one number, one lower
                      case and one upper case letter. Letters can be
                      a-z, å, ä, ö and A-Z, Å, Ä, Ö.");
              return 0;
            }
        } else {
          infoText("Password has invalid length, valid length is 9 - 256 characters.");
          return 0;
        }
    } else {
        infoText("Fill all fields and make sure passwords are identical!");
        return 0;
    }
}

function usernameCheck($uName) {
    if (ctype_alnum($uName)) {
        return 1;
    }
    infoText("Username can only contain alfanumeric characters.");
    return 0;
}

function emailCheck($email) {
    if(preg_match('/[0-9a-zåäöA-ZÅÄÖ]+@[a-zåäöA-ZÅÄÖ]+\.[a-zåäöA-ZÅÄÖ]+/', $email)) {
        return 1;
    }
    infoText("Invalid email address.");
    return 0;
}

function checkDupsFromDB($uName, $email) {
    $dbh = DBHandlerClass::getInstance();

    $table = "users";
    $colums = array("uName", "email");
    $addTerm = "WHERE uName = ? OR email = ?";
    $addAttr = array($uName, $email);

    $rows = $dbh->readFromDB($table, $colums, $addTerm, $addAttr);
//    print_r($rows);
    /* May return 2 rows, but rows[0] contain same username or email so its enough to check it. */
    if (sizeof($rows) && $rows[0]["uName"] === $uName) {
        infoText("Username is already in use.");
        return 0;

    } else if (sizeof($rows) && $rows[0]["email"] === $email) {
        infoText("Email is already in use.");
        return 0;
    }
    return 1;
}

function pwVerify($pw, $hash) {
    if(password_verify($pw, $hash)) {
        return 1;
    }
    infoText("Username or password is incorrect.");
}

function emailValidation($key, $uname) {
    $dbh = DBHandlerClass::getInstance();
    $memcache = $dbh->connectMemcache();
    if($dbh->getMemcacheValue($uname."_code", $memcache) == $key) {
        return 1;
    }
    infoText("Incorrect validation key.");
    return 0;
}
