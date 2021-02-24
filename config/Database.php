<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access"); 
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'jfy_db');

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
    

        if($connection ->ping())
           {
            echo '<script>';
            echo 'console.log("connected")';
            echo '</script>';
           }
        else
	        echo  ($connection->error);
    }
    
    //inserts POST payload data into the 'userprofiles' mysql db
   function insertData($Uname, $Email, $Pass)
   {
        //establishConn();

        $sql = "INSERT INTO userprofiles (UserName, Email, Password) VALUES ('$Uname','$Email','$Pass')"; 

        if(!mysqli_query($connection, $sql))
        {
            echo '<script>';
            echo 'console.log("could not insert data into database")';
            echo '</script>';            
            return false;
        }
        else {
            echo '<script>';
            echo 'console.log("successfully inserted data")';
            echo '</script>';
	        return true;
        }
    }
    
    //retrieves entire database of user information
    public function getAllData()
    {
        //establishConn();

        $sql = "SELECT * FROM userprofiles";

        if(!mysqli_query($connection, $sql))
        {
            printf("Could not retrieve data: %s\n", $connection->error);
            exit();
        }
        else {
            $table = array();

            $tmp = $connection->fetch_array(MYSQLI_NUM);
            $table = $tmp;

            return $table;
        }

    }



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

    }
?>