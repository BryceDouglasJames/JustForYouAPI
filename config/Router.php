<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
class Router{

    private $request;

    //****************MAGIC METHODS****************
    function __construct(RequestInterface $request){
        $this->request = $request;
    }

    //This is a Magic Method. Everytime this object is called it references this function.
    public function __call($CallName, $args){

        //parse arguments for endpoint and type, then add them to dictionary
        list($Endpoint, $Method) = $args;

        $CallName = strtolower($CallName);

        //if calls are not posts or get methods, return handler error
        if(!($CallName === "get" || $CallName === "post")){
            header("Method not allowed");
        }

        //Format endpoint
        $this->{
            $CallName
        }[$this->formatMethodType($Endpoint)] = $Method;

    }


    function __destruct(){
        $this->resolveRoute();
    }
    //*********************************************


    public function resolveRoute(){
        $methodDictionary = $this->{
            strtolower($this->request->REQUEST_METHOD)
        };
        $formatedRoute = $this->grabEndpointName($this->request->REQUEST_URI);

        $Method = $methodDictionary[$formatedRoute];

        if(is_null($Method)){
            header("404 not found");
            return;
        }

        //admin call to fetch uri endpoint
        echo call_user_func_array($Method, array($this->request));
    }

    //formats endpont string
    public function grabEndpointName($string){

        $ReturnPoint = "";
        $ReturnPoint = str_replace("/JustForYouAPI/public", null, $string);

        if (strpos($ReturnPoint, '/index.php') !== false) {
            $ReturnPoint = str_replace("/index.php", null, $ReturnPoint);
        }

        return $ReturnPoint;

    }
    
    private function formatMethodType($string){
        return $string;
    }

}
