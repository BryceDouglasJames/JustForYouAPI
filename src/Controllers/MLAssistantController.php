<?php

include_once __DIR__ .'/../Models/User.php';
include_once __DIR__ .'/../Models/MLAssistant.php';


class MLAssistantController
{
    protected $db;

    public function __construct($con){
        MLAssistant::useConnection($con);
    }

    public function updateScore($payload){
        return MLAssistant::updateUserScore($payload);   
    }
    
    public function getUserScore($payload){
        return MLAssistant::getUserProgressRecord($payload);
    }

    public function train(){
        return MLAssistant::assistant_model();
    }
}

?>