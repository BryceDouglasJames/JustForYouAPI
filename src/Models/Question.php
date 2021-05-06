<?php

    require_once __DIR__ . '/Model.php';


    class Question extends Model{

        public $title;
        public $questionText;
        public $answers = array();
        public $answerWeights = array();

        public function __construct(){
            parent::__construct();
        }
        

        /*
        *   GRABS ALL QUESTIONS BASED ON SUPPLIES CATEGORY
        */
        public function getQuestionByCategory($category){
            $category = self::cleanSQL(self::$conn, $category);
            $sql = 'SELECT * FROM answers WHERE CAID = ' . (int)$category;
            $question = self::query($sql);
            if(!$question){
                throw new Error("Cannot get question.");
            }else{
                return $question;
            }
        }

        /*
        *   GRABS QUESTION BY ID AND RETURN QUERY ONJECY
        */
        public function getQuestionByID($id){
            $id = self::cleanSQL(self::$conn, $id);
            $sql = 'SELECT * FROM answers WHERE AID = ' . (int)$id;
            $question = self::query($sql);
            if(!$question){
                throw new Error("Cannot get question.");
            }else{
                return $question;
            }
        }

        /*
        *   GET ALL QUESTIONS FROM BAD UP TO THE FIRST 100 AND RETURN QUERY OBJECT
        */
        public static function getAllQuestions(){
            $sql = 'SELECT * FROM answers LIMIT 0,100';
            $questions = self::query($sql);
            if(!$questions){
                throw new Error("Cannot get questions.");
            }else{
                return $questions;
            }
        }


        /*
        *   MAP USER ANSWER RESPONSE AND INSERT INTO DB
        */
        public static function recordResponse($UID, $AID, $CAID, $answer, $weight){
            $cols = array("UID", "AID", "CAID", "UserChoice", "ChoiceWeight");
            $vals = array($UID, $AID, $CAID, $answer, $weight);
            self::setTable("responses");
            $answer = self::insert($cols, $vals);
        }

        /*
        *   GRAB QUESTION ANSWER BY ITS ID AND RETURN QUERY OBJECT
        */
        public static function getAnswerByID($id){
            $id = self::cleanSQL(self::$conn, $id);
            $sql = 'SELECT * FROM responses WHERE QUID = 1';
            $answer = self::query($sql);
            if(!$answer){
                throw new Error("Cannot get answers from provided ID.");
            }else{
                return $answer;
            }
        }


        /*
        *   GRAB ALL QUESTIONS USER ANSWERED AND RETURN QUERY OBJECT
        */
        public static function getAllQuestionsAnswered($payload){
            $username = self::cleanSQL(self::$conn, $payload["username"]);
            $user = User::getByUsername($username);

            $sql = 'SELECT * FROM responses WHERE UID = '.$user[0]["UID"];
            $answer = self::query($sql);
            if(!$answer){
                throw new Error("Cannot get answers from provided ID.");
            }else{
                return $answer;
            }
        }
    }
