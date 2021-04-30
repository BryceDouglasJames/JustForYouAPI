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

        public static function getAllQuestions(){
            $sql = 'SELECT * FROM answers LIMIT 0,100';
            $questions = self::query($sql);
            if(!$questions){
                throw new Error("Cannot get questions.");
            }else{
                return $questions;
            }
        }

        public static function recordResponse($UID, $AID, $answer, $weight){
            $cols = array("UID", "AID", "UserChoice", "ChoiceWeight");
            $vals = array($UID, $AID, $answer, $weight);
            self::setTable("responses");
            $answer = self::insert($cols, $vals);
        }

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
    }
