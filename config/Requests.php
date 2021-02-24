<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");

    require_once __DIR__ . "/RequestInterface.php";

    class Requests implements RequestInterface{
        
        function __construct(){
            //create key-value pairings for the request
            foreach($_SERVER as $attribute => $value){
                
                $this->{
                    $this->formatServerAttribute($attribute)
                } = $value;

                //DEBUGGING: Uncomment to see each request attribute value.
                //echo("{$attribute} : {$value}<br>");
            }
        }

        //interface method
        public function getPayloadData(){
            if($this->REQUEST_METHOD === "GET"){
                return;
            }
            if ($this->REQUEST_METHOD == "POST"){
                $body = array();
                foreach($_POST as $key => $PostType){
                    $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                return $body;
            }else{
                header("Method error");
            }
        }

        private function formatServerAttribute($string){
            return $string;
        }
    }