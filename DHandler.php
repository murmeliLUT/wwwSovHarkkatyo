<?php
require_once "recaptchalib.php";
require_once "checker.php";
require_once "utils.php";
require_once "DBHandler.php";

/* File to handel all data steams but login and registeration (excluding recaptha).
 Contorller part of MVC design pattern. Calls utils.php, or javaS.js when using
 AJAX, to print views and DBHandler to execute database queries. */


function recaptchaCheck() { /* Recaptcha checkers. Uses GOOGLE's recaptcha and its keys. */
/* THIS is my private for recaptcha. You can use it to make things more simple. */
    $privatekey = "6Ldz9A4UAAAAAJPKPDuPbJuw0-dmfF5ffvS5Gvz9";

    $response = $_POST["g-recaptcha-response"]; /* Get response from API. */
    $userip = $_SERVER["REMOTE_ADDR"];
    $url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$privatekey&response=$response&remoteip=$userip");

    $results = json_decode($url, TRUE); /* Decodes JSON data. */

    if($results["success"] == 1) { /* If recaptcha success return 1 as TRUE. */
        return 1;

    } else {
        infoText("Invalid recaptcha.");
        return 0;
    }
}

function loadTopics() { /* Calls topics from DBHandler. */
    $dbh = DBHandlerClass::getInstance();
    $table = "topics";
    $colums = array("tID", "tName", "tDT", "users.uName");
    $additionalTerm = "INNER JOIN users on topics.uID = users.uID";

    $topics = $dbh->readFromDB($table, $colums, $additionalTerm, NULL);
    printTopics($topics);
}

function loadPosts($table) { /* Calls posts of specific topic from DBHandler.
    And returns data to AJAX function getPosts via post.php. */
    $dbh = DBHandlerClass::getInstance();
    $colums = array("pID", "postContent", "pDT", "users.uName");
    $additionalTerm = "INNER JOIN users on $table.uID = users.uID";
    /* Join to get username from usertable using user ID */
    $posts = $dbh->readFromDB($table, $colums, $additionalTerm, NULL);

    return $posts;
}

if (isset($_GET["getTopics"]) && (!empty($_GET["getTopics"]))) {
    /* Calls laodTopic function if AJAX function getTopics has set $_GET parameters. */
    loadTopics();

} else if (isset($_POST["topic"]) && !empty($_POST["topic"] &&
            isset($_POST["OPcontent"]) && !empty($_POST["OPcontent"]))) {
/* Calls DBHandler to create new topic if AJAX function createNewTopic has set
 $_GET parameters. */

    $table = strtolower("topic_" . $_POST["topic"]);
    $dbh = DBHandlerClass::getInstance();

    $columsRead = array("tName");
    $addTerm = "WHERE tName = ?";
    $addAttr = array($table);
    $rows = $dbh->readFromDB("topics", $columsRead, $addTerm, $addAttr);

    if (count($rows) > 0) { /* Checks that topic name is not in use already. */
        infoText("Topic with same name already exist.");

    } else { /* When topic name is free creates new table and add topic also
        to topics table which keep track of topics. */
        $dbh->createNewTable($table);

        $colums = array("uID", "tName");
        $values = array($_SESSION["userID"], $table);
        $dbh->writeToDB("topics", $colums, $values);

        $columsPost = array("postContent", "uID");
        $valuesPost = array($_POST["OPcontent"], $_SESSION["userID"]);
        $dbh->writeToDB($table, $columsPost, $valuesPost);
        /* Write Original post to brand new table. */
    }

}

if (isset($_SESSION["topicName"]) && !empty($_SESSION["topicName"]) &&
    isset($_SESSION["userID"]) && !empty($_SESSION["userID"]) &&
    isset($_POST["postContent"]) && !empty($_POST["postContent"])) {
/* If user send new post to topic write post to database by calling DBHandler. */

    $table = $_SESSION["topicName"];
    $dbh = DBHandlerClass::getInstance();

    $colums = array("postContent", "uID");
    $values = array($_POST["postContent"], $_SESSION["userID"]);
    $dbh->writeToDB($table, $colums, $values);

}

function generateValue($lenght) { /* Generate email validation code. */
    return bin2hex(openssl_random_pseudo_bytes($lenght));
}
