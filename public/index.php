<?php
    header("Access-Control-Allow-Origin: *");    
    header("Access-Control-Allow-Headers: Content-Type, origin");  

    include_once '../config/Requests.php';
    include_once '../config/Router.php';
    include_once '../config/Database.php';
    include_once '../src/Controllers/UserController.php';
    include_once '../conSession.php';
    
    $RequestListener = new Requests();
    $router = new Router($RequestListener);   
    $DBInstance = new Database();
    $con = $DBInstance->establishConn();
    $session = new Session();

    $router->get('/', function() {
       return("HELLO WORLD :)");
    });

    $router->post('/data', function($request) {
        $data = $request->getPayloadData();
        return json_encode($data["username"]);
    });

    $router -> post("/users/auth", function($request) use ($con, $DBInstance){
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        $session->login($UserCall->getCurrent($request->getPayloadData()));
        return $UserCall -> authenticate($request->getPayloadData());
    });

    $router->post('/users/grab/all', function($request) use ($con, $DBInstance){
        $data = $request->getPayloadData();

        //pass connection to user model to take care of call
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        return $UserCall->getAllUsers();
        
    });

    $router->post('/users/grab/delete', function($request) use ($con, $DBInstance){
        $data = $request->getPayloadData();

        
        //pass connection to user model to take care of call
        $UserCall = new UserController($con);

        //delete user and return confirmation
        $UserCall -> setCurrentTable('usertable');
        $UserCall->delete($data["DeleteUserWithID"]);
        return $UserCall->getAllUsers();

    });

    $router->post('/users/add', function($request) use ($con, $DBInstance){
        $data = $request->getPayloadData();

        $user = $data['username'];
        $email = $data['email'];
        $pass = $data['password'];

        //pass connection to user model to take care of call
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        $UserCall -> addUser($user, $email, $pass);
        return $UserCall->getAllUsers();

    });


   
?>