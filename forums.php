<?php
/* Site which shows all created topics and where users can create new ones. */
require_once("DHandler.php");
require_once("utils.php");

printHeader("Select topic to read");
loadTopics();
topicFieldPrint(); /* Fields for creating new forum  */
