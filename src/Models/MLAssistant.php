<?php

    require_once __DIR__ . '/Model.php';
    require_once __DIR__ . '/User.php';


    class MLAssistant extends Model{

        public static $thisSum = 0;

        public function __construct(){
            parent::__construct();
        }

        public static function updateUserScore($payload){
            $category = null;
            switch($payload["CAID"]){
                case 1:
                    $category = "Mental";
                    break;
                case 2:
                    $category = "Personal";
                    break;
                case 3:
                    $category = "Diet";
                    break;
                case 4:
                    $category = "Fitness";
                    break;
            }
            self::setTable("weeklyscores");
            $thisday = self::getDayOfWeek();
            $thiscategory = $payload["CAID"];
            $encodedKey = base64_encode(self::cleanSQL(self::$conn, $payload['username']));
            $thisScore = self::assistant_model($payload);    //TODO generate this score from regression model based on user prediction

            $cols = array($thisday);
            $vals = array($thisScore);
            self::update($cols, $vals, "'".$category."_".$encodedKey."'");

            
        }

        public static function getDayOfWeek(){
            $current = time();
            $day = (floor((int)$current / 86400) + 4) % 7;
            switch($day){
                case 0:
                    $day = "Sunday";
                    break;
                case 1:
                    $day = "Monday";
                    break;
                case 2:
                    $day = "Tuesday";
                    break;
                case 3:
                    $day = "Wednesday";
                    break;
                case 4:
                    $day = "Thursday";
                    break;
                case 5:
                    $day = "Friday";
                    break;
                case 6:
                    $day = "Saturday";
                    break;
                default:
                    $day ="?";
                    break;
            }
            return $day;
        }
        
        public static function getUserProgressRecord($payload){
            $returnArray = array();
            $username = $payload["username"];
            $user = User::getByUsername($username);
            $sql = "SELECT * FROM weeklyscores WHERE UID = " .$user[0]["UID"];
            $answer = self::query($sql);
            if(!$answer){
                throw new error("Error finding activity record.");
            }else{
                while($row = $answer->fetch_row()){
                    $buffer = array(
                        'Activity_Index' => $row[0],
                        'Monday' => $row[2],
                        'Tuesday' => $row[3],
                        'Wednesday' => $row[4],
                        'Thursday' => $row[5],
                        'Friday' => $row[6],
                        'Saturday' => $row[7],
                        'Sunday' => $row[8]
                    ); 
                    array_push($returnArray, $buffer); 
                }
            }
            return $returnArray;
        }

        public static function getResponsesByCategory($payload){
            $returnArray = array();
            $average = 0;
            $username = $payload["username"];
            $user = User::getByUsername($username);
            $sql = "SELECT * FROM responses WHERE UID = " .$user[0]["UID"] . " AND CAID = " . $payload["CAID"];
            $answer = self::query($sql);
            if(!$answer){
                throw new error("Error finding activity record.");
            }else{
                while($row = $answer->fetch_row()){
                    $buffer = array(1, rand(1, 100), $row[5]);
                    $average += (float) $row[5]; 
                    array_push($returnArray, $buffer); 
                }
            }
            return array($returnArray, $average/sizeof($returnArray));
        }

        public static function assistant_model($payload){
            $digestedResponse = self::getResponsesByCategory($payload);
            $data = $digestedResponse[0];
            $const1 = $digestedResponse[1];
            $const2 = 1;

            $learningRate = 0.00000001;

            for ($i = 0; $i < 10; $i++) {

                //FIX THIS FUNCTION IF ABLE
                //$errorSum = self::error_function($const1, $const2, $data);
                $correctConst1 = self::fixConst1($learningRate, $const1, $const2, $data) ;
                $correctConst2 = self::fixConst2($learningRate, $const1, $const2, $data) ;

                $const1 = $correctConst1; 
                $const2 = $correctConst2;
            }

            return (int)$const1 - (int)$const2;
        }


        public static function error_function($const1, $const2, $data){
            $n = sizeof($data);
            self::$thisSum = 0;
            for($i = 0; $i < sizeof($data); $i++){
                array_map(
                    function ($position) use ($const1, $const2) {
                        self::$thisSum += ($position[2] - ($const1 * $position[0] + $const2 * $position[1])) ** 2;
                    },
                    $data
                );
            }

            return self::$thisSum/$n;
        }

        public static function gradient_decent($CurrentPosition, $const1, $const2, $data){
            $n = sizeof($data);
            $firstHalf = -2/$n;

            $summation =  self::decentSummationFunction($CurrentPosition, $const1, $const2, $data);

            return $firstHalf * $summation;
        }

        public static function decentSummationFunction($CurrentPosition, $const1, $const2, $data){
            self::$thisSum = 0;
            for($i = 0; $i < sizeof($data); $i++){
                array_map(
                    function ($position) use ($CurrentPosition, $const1, $const2) {
                        self::$thisSum += ($position[2] - ($const1 * $position[0] + $const2 * $position[1]) * $position[$CurrentPosition]);
                        //print_r($position);
                    },
                    $data
                );
            }
            return self::$thisSum;  
        }

        public static function fixConst1($learningRate, $const1, $const2, $data){
            return $const1 - $learningRate * self::gradient_decent(0, $const1, $const2, $data);
        }

        public static function fixConst2($learningRate, $const1, $const2, $data){
            return $const2 - $learningRate * self::gradient_decent(1, $const1, $const2, $data);

        }
    }
