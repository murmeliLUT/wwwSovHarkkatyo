<?php
/* Singleton class which handles ALL database queries. Model part of MVC design pattern. */

class DBHandlerClass {
    private static $DBH = null;
    private static $DNS = "mysql:host=localhost; dbname=ht; charset=utf8";
    private static $USER = "ht"; /* Don't change these unless you know what to do. */
    private static $PASS = "harkkatyö"; /*forumofgreenshades*/

	private function construct__() {}

    public static function getInstance() { /* Singleton pattern get instance method. */
        static $DBH = null;
        if ($DBH === null) {
            $DBH = new DBHandlerClass();
        }
        return ($DBH);
    }

    function readFromDB($table, $colums, $additionalTerm, $addAttr) {
        /* Universal database reader. Takes parameters: Table name, which colums
        will be read, if there is additional term  like WHERE and possible values
        for additional term which are binded to ?-placeholder. */
        try {
            $db = new PDO(DBHandlerClass::$DNS, DBHandlerClass::$USER, DBHandlerClass::$PASS);
            /* Database connection */

            $queryS = "SELECT " . array_shift($colums); /* Builds query string dynamically. */
            foreach ($colums as $colum) {
                $queryS = $queryS . ", " . $colum;
            }
            $queryS = $queryS . " FROM " . $table . " " . $additionalTerm;

            $stmt = $db->prepare($queryS); /* Prepare statement before binding. */

            if ($addAttr != NULL) { /* Bind values to ?-placeholders if there are some. */
                for ($j = 0; $j < sizeof($addAttr); $j++) {
                    $stmt->bindValue($j+1, $addAttr[$j]);
                }
            }
            $stmt->execute(); /* execute statement */
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//$stmt->debugDumpParams();
            $db = NULL; /* Closes database connection */
            return $rows; /* Return asked information. */

        } catch (PDOException $exRead) { /* If something goes wrong while reading datbase. */
            $db = NULL;
//            print($exRead->getMessage());
        }
    }

    function writeToDB($table, $colums, $values) {
        /* Universal database writer. Takes parameters: Table name, which colums
        will be writen as array and values for those colums as array.
        Values are binded to ?-placeholder to inceace security by decreasing SQL injections. */
        try {
            $db = new PDO(DBHandlerClass::$DNS, DBHandlerClass::$USER, DBHandlerClass::$PASS);
            /* Database connection */

            $queryS ="INSERT INTO " . $table . " (" . array_shift($colums);
            /* Builds query string dynamically. */

            foreach ($colums as $colum) {
                $queryS = $queryS . ", " . $colum;
            }
            $queryS = $queryS . ") VALUES(?";

            for ($i = 1; $i < sizeof($values); $i++) {
                $queryS = $queryS . ", ?";
            }
            $queryS = $queryS . ")";

            $stmt = $db->prepare($queryS);

            for ($j = 0; $j < sizeof($values); $j++) { /* Bind parameters to ?-placeholders. */
                $stmt->bindParam($j+1, $values[$j]);
            }
            $stmt->execute(); /* execute statement */
//$stmt->debugDumpParams();
            $db = NULL; /* Closes database connection */

        } catch (PDOException $exWrite) {
            $db = NULL;
//            print($exWrite->getMessage());
        }
    }

    function createNewTable($table) {
        /* Function to create new (topic) tables to database
        this is used when new topic is created. */
        try {
            $db = new PDO(DBHandlerClass::$DNS, DBHandlerClass::$USER, DBHandlerClass::$PASS);
            $queryS = "CREATE TABLE " . $table . " (pID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY," .
            " postContent LONGBLOB, uID INT(6), pDT TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";

            $stmt = $db->prepare($queryS);
            $stmt->execute();
            $db = NULL; /* Closes database connection */

        } catch (PDOException $ex) {
            $db = NULL;
        }

    }

    function connectMemcache() { /* Function for connecting to memcache */
        $memcache = new Memcache();
        $memcache->addServer('127.0.0.1', 11211) or die ("Could not connect.");
        return $memcache; /* Return memcache object. */
    }

    function setMemcacheValue($key, $memcache, $value) { /* Fucntion to set values to memcache. */
        if (isset($memcache)) {
            /* Key which "place" value is stored and 300 means how many seconds
            data is stored in memcache. This is basically time to enter email
            validation key in registeration. */
            $memcache->set($key, $value, false, 300) or die ("Failed to save data at the server.");
        }
    }

    function getMemcacheValue($key, $memcache) { /* Fucntion to get values from memcache. */
        if (isset($memcache)) {
            $temp = $memcache->get($key); /* If there are data in asked key location return data. */
            if($temp) {
                return $temp;
            }
        }
        return null;
    }
}
