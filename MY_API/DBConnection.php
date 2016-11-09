<?php
namespace DB;

use \PDO;

class DBConnection {

    private static $instances;

    private function __construct() {}

    public static function getInstance($db_host = 'localhost', $db_name = 'dt', $db_user = 'root', $db_pass = '') {
        if (empty(self::$instances[$db_host][$db_name])) {
            try {
                $dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name  .
                    ';charset=utf8;';
                self::$instances[$db_host][$db_name] = new PDO($dsn, $db_user, $db_pass);
                self::$instances[$db_host][$db_name]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        return self::$instances[$db_host][$db_name];
    }
}