<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");

    include_once '../config/Requests.php';
    include_once '../config/Router.php';
    include_once '../config/Database.php';

    $RequestListener = new Requests();
    $router = new Router($RequestListener);
    
   

    $router->get('/', function() {
       return("HELLO WORLD :)");
    });


    $router->get('/profile', function($request) {
        
    });

    $router->post('/data', function($request) {
        $data = $request->getPayloadData();
        return json_encode($data["username"]);
    });

    $router->post('/users/grab/all', function($request){
        $data = $request->getPayloadData();
        $DBInstance = new Database();
        $con = $DBInstance->establishConn();
        
        if($DBInstance->pingServer($con)){
            $result = $DBInstance->getAllUserData($con);
            
        }

        $con->close();
        return json_encode($result);
    });

    $router->post('/login', function($request){
        return ("<h1>LOGIN<h1>");
    });
   
    // does not currently invoke the insertData() function
    $router->post('/register', function($request){
        $Database->insertData("one","two","three"); //test values
        
        //$data = $request->getPayloadData();
        // $Database->insertData($data['name'],$data['email'], $data['pass']);
    });

    // does not currently invoke the getAllData() function
    $router->get('/returnUsers', function($request) {
        $printout = $Database->getAllData();
    
        return $printout;
    });

?>