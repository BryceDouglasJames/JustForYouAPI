<?php

    //FOR DEVELOPMENT
    /*
        TEST DATABASE NAME: test
    /*
        INSERT TEST TABLE QUERY::
        CREATE TABLE `usertable`( `user_id` INT NOT NULL AUTO_INCREMENT,`name` varchar(100) NOT NULL, `email` varchar(100) NOT NULL, `pass` varchar(100) NOT NULL, PRIMARY KEY(user_id));
    */

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access"); 
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'test');

    class Database{

        private $connection;

        //establishes a connection with the server and db
        function establishConn(){
            $connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD,DB_NAME);
            if($connection ->connect_errno)
            {
                printf("connection failed: %s \n", $connection->connect_error);
                exit();
            }
            return $connection;
        }
        
    }
?>