<?php
require_once("DHandler.php");

loadPosts($_SESSION["topicName"]);
postFieldPrint();
