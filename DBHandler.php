<?php

class DBHandlerClass {
    private static $DBH = null;
    private static $DNS = "mysql:host=localhost; dbname=ht; charset=utf8";
    private static $USER = "ht";
    private static $PASS = "harkkatyö"; /*forumofgreenshades*/

	private function construct__() {}

    public static function getInstance() {
        static $DBH = null;
        if ($DBH === null) {
            $DBH = new DBHandlerClass();
        }
        return ($DBH);
    }

    function readFromDB($table, $colums, $additionalTerm, $addAttr) {
        try {
            $db = new PDO(DBHandlerClass::$DNS, DBHandlerClass::$USER, DBHandlerClass::$PASS);

            $queryS = "SELECT " . array_shift($colums);
            foreach ($colums as $colum) {
                $queryS = $queryS . ", " . $colum;
            }
            $queryS = $queryS . " FROM " . $table . " " . $additionalTerm;

            $stmt = $db->prepare($queryS);

            if ($addAttr != NULL) {
                for ($j = 0; $j < sizeof($addAttr); $j++) {
                    $stmt->bindValue($j+1, $addAttr[$j]);
                }
            }
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//$stmt->debugDumpParams();
            $db = null;
            return $rows;

        } catch (PDOException $exRead) {
            print($exRead->getMessage());
        }
    }

    function writeToDB($table, $colums, $values) {
        try {
            $db = new PDO(DBHandlerClass::$DNS, DBHandlerClass::$USER, DBHandlerClass::$PASS);

            $queryS ="INSERT INTO " . $table . " (" . array_shift($colums);
            foreach ($colums as $colum) {
                $queryS = $queryS . ", " . $colum;
            }
            $queryS = $queryS . ") VALUES(?";

            for ($i = 1; $i < sizeof($values); $i++) {
                $queryS = $queryS . ", ?";
            }
            $queryS = $queryS . ")";

            $stmt = $db->prepare($queryS);

            for ($j = 0; $j < sizeof($values); $j++) {
                $stmt->bindParam($j+1, $values[$j]);
            }
            $stmt->execute();
//$stmt->debugDumpParams();
            $db = NULL;

        } catch (PDOException $exWrite) {
            print($exWrite->getMessage());
        }
    }

    function createNewTable($table) {
        $db = new PDO(DBHandlerClass::$DNS, DBHandlerClass::$USER, DBHandlerClass::$PASS);
        $queryS = "CREATE TABLE topic_" . $table . " (pID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY," .
        " postContent LONGBLOB, uID INT(6), pDT TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";

        $stmt = $db->prepare($queryS);
        $stmt->execute();
        $db = NULL;
    }

    function connectMemcache() {
        $memcache = new Memcache();
        $memcache->addServer('127.0.0.1', 11211) or die ("Could not connect.");
        return $memcache;
    }

    function setMemcacheValue($key, $memcache, $value) {
        if (isset($memcache)) {
            $memcache->set($key, $value, false, 300) or die ("Failed to save data at the server.");
        }
    }

    function getMemcacheValue($key, $memcache) {
        if (isset($memcache)) {
            $temp = $memcache->get($key);;
            if($temp) {
                return $temp;
            }
        }
        return null;
    }
}
