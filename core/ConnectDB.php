<?php

namespace core;

use PDO;
use PDOException;

class ConnectDB {
    // Hold the class instance.
    private static $instance = null;
    private $conn;

    // The db connection is established in the private constructor.
    private function __construct()
    {
        include(__DIR__ . "/../config.php");

        try {
            $db = new PDO('mysql:host='.$config['HOST'].';port='.$config['PORT'].';dbname='.$config['DATABASE'], $config['USERNAME'], $config['PASSWORD']);
            $this->conn = $db;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new ConnectDb();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
