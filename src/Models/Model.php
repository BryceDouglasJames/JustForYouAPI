<?php
    abstract class Model{

        private static $conn;
        private static $table;

        public $id;
        public $created_at;
        public $updated_at;
        public $deleted_at;

        protected $private_fields = [];

        public function __construct(){
            settype($this->id, 'integer');
        }


        //query all the users, return array of JSON objects
        public function getAllUserData(){
            $returnTable = array();
            $result = self::query("SELECT * FROM " . self::getTable());
            if(!$result){
                throw new Exception("There are no users in selected table.");
            }else {
                while($row = $result->fetch_row()){
                    $buffer = array(
                        'UID' => $row[0],
                        'Name' => $row[1],
                        'Email' => $row[2],
                        'Pass' => $row[3]
                    );                   
                    array_push($returnTable, $buffer);
                }
            }
            return $returnTable;
        }

        //insert data in model table 
        public function insert($cols, $values){
            //value buffers
            $colString = "";
            $valueString = "";

            //throw query arguments into array
            for($i = 0; $i < count($cols); $i++){
                if($i === count($cols) - 1){
                    $colString = $colString . $cols[$i];
                }else{
                    $colString = $colString . $cols[$i] . ", ";
                }
            }
            for($i = 0; $i < count($values); $i++){
                if($i === count($values) - 1){
                    $valueString = $valueString . "'" . $values[$i] . "'";
                }else{
                    $valueString = $valueString . "'" . $values[$i] . "'" . ", ";
                }
            }

            //send query, throw error if query isn't formatted properly.
            $result = self::query("INSERT INTO " . self::getTable() . " (" . $colString . ") VALUES (" . $valueString . ")");
            if(!$result){
                throw new Exception("Error inserting into table.");
            }

        }

        //update record by selected ID with field values
        public function updateById($id, $fields){
           
        }


        //Grab user ID and delte it accordingly
        public static function deleteById($id){
            $sql = 'DELETE FROM ' . self::getTable() . ' WHERE user_id= ' . $id;
            $entries = self::query($sql);
            return $entries;
        }

        //return user by ID
        public static function getById($id){
            $entries = self::getByField('id', $id);
            if ($entries == null) return null;
            return $entries[0];
        }


        //Grab by username and send back an array of selected user props
        public static function getByUsername($username){
            $sql = "SELECT * FROM " . self::getTable() . " WHERE name = '" . $username . "'";
            $result = self::query($sql);
            if(!$result || $result->fetch_row() === null){
                return false;
            }else{
                $row = $result->fetch_row();
                $buffer = array(
                    'UID' => $row[0],
                    'Name' => $row[1],
                    'Email' => $row[2],
                    'Pass' => $row[3]
                );      
                return $buffer;
            }
        }

        
        //sends query based off of specified columns
        public static function getByField($field, $value){
            $sql = 'SELECT * FROM ' . self::getTable() . ' WHERE ' . $field . '='. $value;
            $entries = self::query($sql);
            return $entries;
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
        private function query($sql){
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

        private function getTable(){
            return self::$table;
        }
    }

    //refernce private variables outside of scope
    function get_public_vars($instance)
    {
        return get_object_vars($instance);
    }

?>