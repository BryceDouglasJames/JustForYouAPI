<?php

    include_once __DIR__ .'/../Models/Question.php';
    include_once __DIR__ .'/../Models/User.php';


    class QuestionController{

        protected $db;

        public function __construct($con){
            Question::useConnection($con);
        }


        /*
        *   Method grabs all questions and returns a random index to client
        */
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
            return($returnQuestion);
        }


        /*
        *   Method queries question by category and returns to client
        */
        public function questionByCategory($payload){
            $questionArray = array();
            $question = Question::getQuestionByCategory($payload["category"]);
            while($row = $question->fetch_row()){
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
            //print_r($questionArray);
            $index = rand(0, sizeof($questionArray) - 1);
            $returnQuestion = $questionArray[$index];
            return $returnQuestion;
        }

        /*
        *   Method queries question from DB and grabs their answrs to return to client
        *   question popup.
        */
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
            return $val;
        }


        /*
        *   Method ingests user question response payload, retieves user properties from
        *   DB, decodes question weights from row, and stores update into table and trigger ML 
        *   assistant update
        */
        public function answerQuestion($payload){
            $questionArray = array();
            $AID = $payload["AID"];
            $CAID = $payload["CAID"];
            $AnswerIndex = $payload["questionAnswer"];
            $user = User::getByUsername($payload["username"]);
            $UID = $user[0]["UID"];
            $question = Question::getQuestionByID($AID);
            while($row = $question->fetch_row()){
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

            $weight = json_decode($questionArray[0]["Answer_Weights"]);
            foreach ($weight as $key => $value) {
                if($key == $AnswerIndex){
                    $weight = $value;
                    break;
                }
            }

            return Question::recordResponse($UID, $AID, $CAID, $AnswerIndex, $weight);
        }

    }