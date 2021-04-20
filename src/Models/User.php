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

    public function hashPassword($password){
        $salt1 = "JUST";
        $salt2 = "4YOU";
        $token = hash("ripemd256", "$salt1$password$salt2");
        return $token;
    }

    public function verifyPassword($password){
        $salt1 = "JUST";
        $salt2 = "4YOU";
        //return password_verify($password, $_SESSION[hash("ripemd256", "$salt1$password$salt2")]);
        $answer = array();
        $sql = "SELECT * FROM  usertable  WHERE password = '" . hash("ripemd256", "$salt1$password$salt2") . "'";
        $result = self::query($sql);
        if(!$result){
            throw new error("OH NO");
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
    
    public function recordLogin($UID){
        $current = date_create();
        $formatted = date_format($current, 'Y-m-d H:i:s');
        self::update(array("last_login"), array('"'.$formatted.'"'), $UID);            
    }

    public function recordLogout($UID){
        $current = date_create();
        $formatted = date_format($current, 'Y-m-d H:i:s');
        self::update(array("last_logout"), array('"'.$formatted.'"'), $UID);        
    }
    
}
