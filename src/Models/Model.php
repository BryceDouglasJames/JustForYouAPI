<?php
    abstract class Model{

        public static $conn;
        private static $table;

        public $id;
        public $created_at;
        public $updated_at;
        public $deleted_at;

        protected $private_fields = [];

        public function __construct(){
            settype($this->id, 'integer');
        }


        //insert data in model table 
        public function insert($cols, $values){
            //value buffers
            $colString = "";
            $valueString = "";

            //throw query arguments into array
            for($i = 0; $i < count($cols); $i++){
                $colString = self::cleanSQL(self::$conn, $colString);
                if($i === count($cols) - 1){
                    $colString = $colString . $cols[$i];
                }else{
                    $colString = $colString . $cols[$i] . ", ";
                }
            }
            for($i = 0; $i < count($values); $i++){
                $valueString = self::cleanSQL(self::$conn, $valueString);
                if($i === count($values) - 1){
                    $valueString = $valueString . "'" . $values[$i] . "'";
                }else{
                    $valueString = $valueString . "'" . $values[$i] . "'" . ", ";
                }
            }

            //send query, throw error if query isn't formatted properly.
            $sql = "INSERT INTO " . self::getTable() . " (" . $colString . ") VALUES (" . $valueString . ")";
            echo $sql;
            $result = self::query($sql);
            if(!$result){
                throw new Exception("Error inserting into table.");
            }

        }

        public function update($cols, $values, $updateID){
            $PRIMARY_KEY_MAP = array("usertable" => "UID", "userdata" => "PROVID", "session" => "SID", "userposts" => "POSTID");

            $updateString = "";

            for($i = 0; $i < count($cols); $i++){
                $updateString = self::cleanSQL(self::$conn, $updateString);
                if($i === count($values) - 1){
                    $updateString = $updateString . $cols[$i] . "=" . $values[$i];
                }else{
                    $updateString = $updateString . $cols[$i] . "=" . $values[$i] . ", ";
                }
            }

            $sql = "UPDATE  " . self::getTable() . " SET " . $updateString . " where " . self::getTable() . "." . $PRIMARY_KEY_MAP[self::getTable()] . "=" . $updateID . "";
            //echo $sql; 
            $result = self::query($sql);
            if(!$result){
                throw new Exception("Error updating value in table");
            }
        }

        public function getID($username){
            $id = 0;
            $username = self::cleanSQL(self::$conn, $username);
            $sql = "SELECT UID FROM usertable WHERE name = '" . $username . "'";
            $result = self::query($sql);
            if(!$result){
                return false;
            }else{
                while($row = $result -> fetch_row()){
                    $id = $row[0];
                }
            }
            return $id;
        }

        //sends query based off of specified columns
        public static function getByField($field, $value){
            $field = self::cleanSQL(self::$conn, $field);
            $value = self::cleanSQL(self::$conn, $value);
            $sql = 'SELECT * FROM ' . self::getTable() . ' WHERE ' . $field . '='. $value;
            $entries = self::query($sql);
            return $entries;
        }
        
        public function cleanSQL($connection, $token){
            return self::mysql_entities_fix_string($connection, $token);
        }
        public function mysql_entities_fix_string($connection, $string){
            return self::mysql_fix_string($connection, $string);
        }
        public function mysql_fix_string($connection, $string){
            if (get_magic_quotes_gpc()) {
                $string = stripslashes($string);
            }
            return $string;
        }


        /*//SAVE FOR FUTURE WHEN WE ARE HASHING PASSWORDS AND RESPONSES
        public static function getPrivateFields($arr){
            $obj = new static();
            foreach ($arr as $field => $value) {
                $obj->$field = $value;
            }
            return $obj;
        }

        public function setPrivateFields(){
            $fields = get_public_vars($this);
            foreach ($this->private_fields as $key) {
                unset($fields[$key]);
            }
            return $fields;
        }

        private function getUniqueFields()
        {
            return array_diff(
                array_keys(get_public_vars($this)),
                ["id", "created_at", "updated_at", "deleted_at"]
            );
        }*/

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


        //sends simple query across
        public function query($sql){
            $result = self::$conn -> query($sql);
            return $result;
        }

        //GETTERS/SETTERS
        public function useConnection($conn){
            self::$conn = $conn;
        }
    
        public function setTable($thisTable){
            self::$table = $thisTable;
        }

        public function getTable(){
            return self::$table;
        }
    }

    //refernce private variables outside of scope
    function get_public_vars($instance)
    {
        return get_object_vars($instance);
    }

?>