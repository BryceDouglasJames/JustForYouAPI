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
        
    }
