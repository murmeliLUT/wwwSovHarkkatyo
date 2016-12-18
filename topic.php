<?php
require_once("DHandler.php");
require_once("utils.php");

 /* Site which calls funtions to show all post of current topic. */

printHeader($_SESSION["topicName"]);
$posts = loadPosts($_SESSION["topicName"]);
printPosts($posts);
postFieldPrint(); /* Fields for new post. */
PDFbutton(); /* Button to download posts of topic as PDF */
