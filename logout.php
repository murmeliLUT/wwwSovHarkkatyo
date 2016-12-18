<?php  require_once("utils.php");
 /* When logout button is clicked this file deastroyes session. Which is
 considered as logout. Redirect to login page afterwards. */
session_destroy() ;
header("Location: http://www.ht.dev/index.php?p=login");
