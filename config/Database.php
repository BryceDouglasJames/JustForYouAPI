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
        
        //query all the users, return array of JSON objects
        public function getAllUserData($thisCon){
            $table = array();
            $sql = "SELECT * FROM usertable";
            $result = $thisCon->query($sql);
            if(!$result){
                throw new Exception("There is no data in selected table");
            }else {
                while($row = $result->fetch_row()){
                    $bufferObject = array(
                        'UID' => $row[0],
                        'Name' => $row[1],
                        'Email' => $row[2],
                        'Pass' => $row[3]
                    );
                    array_push($table, $bufferObject);
                }
            }
            return $table;
        }

        //checks to make sure Database is alive
        public function pingServer($thisCon){
            $answer = false;
            if($thisCon -> ping()){
                $answer = true;
            }else{
                throw New Exception("Error pinging server");
            }
            return $answer;
        }
    }


    //RE IMPLEMENT
    //inserts POST payload data into the 'userprofiles' mysql db
   /*function insertData($thisCon, $thisQuery)
   {
        if(!mysqli_query($connection, $sql))
        {
            throw new Exception("")
        }
        else {
            echo '<script>';
            echo 'console.log("successfully inserted data")';
            echo '</script>';
	        
        }
    }*/
    

/*
    $query = "SELECT 1 FROM 'user_credentials'";

    $query_result = $connection->query($query);

    if(empty($query_result))
    {
        $query = "CREATE TABLE user_credentials (
        ID int(6) AUTO_INCREMENT, EMAIL varchar(225) NOT NULL, PASSWORD varchar(225) NOT NULL)";

        $query_result = ($connection ->query($query));
    }
    */

?>