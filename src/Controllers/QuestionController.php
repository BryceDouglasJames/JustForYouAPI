<?php

    include_once __DIR__ .'/../Models/Question.php';


    class QuestionController{

        protected $db;

        public function __construct($con){
            Question::useConnection($con);
        }

        public function getRandomQuestion(){
            $questionArray = array();
            $questions = Question::getAllQuestions();
            while($row = $questions->fetch_row()){
                $buffer = array(
                    'Question_ID' => $row[0],
                    'Category_ID' => $row[1],
                    'Question_Title' => $row[2],
                    'Question_Text' => $row[3],
                    'Answers' => $row[4],
                    'Answer_Weights' => $row[5]
                ); 
                array_push($questionArray, $buffer);    
            }

            
            $index = rand(0, sizeof($questionArray) - 1);
            $returnQuestion = $questionArray[$index];
            //$QID = $returnQuestion["Question_ID"];
            //$answers = self::getAnswers($QID);
            //$returnQuestion["Answers"] =  $answers["Answers"];
            return($returnQuestion);
        }

        public function getAnswers($Question_ID){
            $val = array();
            $answers = Question::getAnswerByID($Question_ID);
            while($row = $answers->fetch_row()){
                $temp = stripslashes($row[3]);
                $buffer = array(
                    'Answers' => $temp
                ); 
                $val["Answers"] = $buffer["Answers"];
            }
            
            //$val["Answers"] = self::cleanAnswer($val["Answers"]);
            return $val;
        }

        /*private function cleanSlashes($string){

            echo($string."\n\n\n\n");
            $temp = "";
            $chars = str_split($string);
            foreach($chars as $char){
                if($char != "\\"){ 
                    $temp = $temp."".$char;
                }
            }

            return $temp;
        }*/

    }