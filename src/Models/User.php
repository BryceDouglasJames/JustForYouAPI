<?php


require_once __DIR__ . '/Model.php';

class User extends Model
{
    public $username;
    public $password_hash;
    public $user_role;
    public $id;

    protected $private_fields = ["password_hash"];

    private static $secret_key = 'secret_key';

    public function __construct()
    {
        parent::__construct();
    }

    /*
    *   SALTS USER PASSWORD AND HASHES STRING
    */
    public function hashPassword($password){
        $salt1 = "JUST";
        $salt2 = "4YOU";
        $token = hash("ripemd256", "$salt1$password$salt2");
        return $token;
    }

    /*
    *   GRABS SALT, GRABS USER, CHECK TO SEE IF PASSWORD MATCHES
    */
    public function verifyPassword($password){
        $salt1 = "JUST";
        $salt2 = "4YOU";
        $answer = array();
        $sql = "SELECT * FROM  usertable  WHERE password = '" . hash("ripemd256", "$salt1$password$salt2") . "'";
        $result = self::query($sql);
        if(!$result){
            throw new error("Cannot verify password");
            return false;
        }else{
            while($row = $result->fetch_row()){
                $buffer = array(
                    'Pass' => $row[3],
                ); 
                array_push($answer, $buffer);    
            }
            return $answer;
        }
    }
    
    /*
    *   QUERY USER FROM PAYLOAD AND RETURN USER PROPERTIES   
    */    
    public static function getByUsername($username){
        $answer = array();
        $username = self::cleanSQL(self::$conn, $username);
        $sql = "SELECT * FROM usertable WHERE name = '" . $username . "'";
        $result = self::query($sql);
        if(!$result){
            return false;
        }else{
            while($row = $result->fetch_row()){
                $buffer = array(
                    'UID' => $row[0],
                    'Name' => $row[1],
                    'Email' => $row[2],
                    'Pass' => $row[3],
                    'NewUser' => $row[4]
                ); 
                array_push($answer, $buffer);    
            }
            return $answer;
        }
    }

    /*
    *   RETURN USER BY ID
    */
    public static function getById($id){
        $id = self::cleanSQL(self::$conn, $id);
        $entries = self::getByField('id', $id);
        if ($entries == null) return null;
        return $entries[0];
    }

    /*
    *   DELETE USER BY ID
    */    
    public static function deleteById($id){
        $id = self::cleanSQL(self::$conn, $id);
        $sql = 'DELETE FROM ' . self::getTable() . ' WHERE user_id= ' . $id;
        $entries = self::query($sql);
        return $entries;
    }


    /*
    *   QUERY ALL THE USERS, RETURN ARRAY OF OBJECT PROPERTIES   
    */
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
    

    /*
    *  CREATES TIMESTAMP AND UPDATES USER LOGIN   
    */
    public function recordLogin($UID){
        $current = date_create();
        $formatted = date_format($current, 'Y-m-d H:i:s');
        self::update(array("last_login"), array('"'.$formatted.'"'), $UID);            
    }


    /*
    *  CREATES TIMESTAMP AND UPDATES USER LOGOUT  
    */
    public function recordLogout($UID){
        $current = date_create();
        $formatted = date_format($current, 'Y-m-d H:i:s');
        self::update(array("last_logout"), array('"'.$formatted.'"'), $UID);        
    }

    
}
