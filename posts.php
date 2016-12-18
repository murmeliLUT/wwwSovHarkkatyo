<?php
 /* Page for sending post info to ajax function getPost() which then adds user's
  posts to site without refresing page. Data is JSON encoded and sent via printing. */
require_once "utils.php";
require_once "DHandler.php";

if (isset($_SESSION["userID"]) && !empty($_SESSION["userID"]) &&
isset($_SESSION["topicName"]) && !empty($_SESSION["topicName"])) {

    $posts = loadPosts($_SESSION["topicName"]); /* Gets posts returned from DHandler. */
    print(json_encode($posts));
    /* JSON encodes data and print it (="send") it to AJAX function getPost.*/
}
