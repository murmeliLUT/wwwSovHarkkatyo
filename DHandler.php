<?php
require_once "recaptchalib.php";
require_once "checker.php";
require_once "utils.php";
require_once("DBHandler.php");


function recaptchaCheck() {
    $privatekey = "6Ldz9A4UAAAAAJPKPDuPbJuw0-dmfF5ffvS5Gvz9";
    $response = $_POST["g-recaptcha-response"];
    $userip = $_SERVER["REMOTE_ADDR"];
    $url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$privatekey&response=$response&remoteip=$userip");

    $results = json_decode($url, TRUE);
    /*    $_POST["recaptcha_challenge_field"],
        $_POST["recaptcha_response_field"]);*/

    if($results["success"] == 1) {
        return 1;

    } else {
        infoText("Invalid recaptcha.");
        return 0;
    }
}

function loadTopics() {
    $dbh = DBHandlerClass::getInstance();
    $table = "topics";
    $colums = array("tID", "tName", "tDT", "users.uName");
    $additionalTerm = "INNER JOIN users on topics.uID = users.uID";

    $topics = $dbh->readFromDB($table, $colums, $additionalTerm, NULL);

    printTopics($topics);
}

function loadPosts($table) {
    $dbh = DBHandlerClass::getInstance();
    $colums = array("pID", "postContent", "pDT", "users.uName");
    $additionalTerm = "INNER JOIN users on $table.uID = users.uID";

    $posts = $dbh->readFromDB("topic_".$table, $colums, $additionalTerm, NULL);

    printPosts($posts);
}

if (isset($_GET["getTopics"]) && (!empty($_GET["getTopics"]))) {
    loadTopics();
}


if (isset($_POST["topic"]) && !empty($_POST["topic"])) {
    $table = $_POST["topic"];
    $dbh = DBHandlerClass::getInstance();

    $columsRead = array("tName");
    $addTerm = "WHERE tName = ?";
    $rows = $dbh->readFromDB("topics", $columsRead, $addTerm, "topic_".$table);

    if (sizeof($rows) > 0) {
        infoText("Topic with same name already exist.");

    } else {
        $dbh->createNewTable($table);

        $colums = array("uID", "tName");
        $values = array($_SESSION["userID"], "topic_".$table);
        $dbh->writeToDB("topics", $colums, $values);
//        loadTopics();
    }
}

if (isset($_GET["postName"]) && (!empty($_GET["postName"]))) {
    loadPosts($_SESSION["topicName"]);
}


if (isset($_POST["topicName"]) && !empty($_POST["topicName"]) &&
    isset($_POST["posterName"]) && !empty($_POST["posterName"]) &&
    isset($_POST["content"]) && !empty($_POST["content"])) {
    $table = $_POST["topicName"];
    $dbh = DBHandlerClass::getInstance();

    $colums = array("postContent", "uID");
    $values = array($_POST["content"], $_SESSION["userID"]);
    $dbh->writeToDB($table, $colums, $values);
//    loadPosts();

}
