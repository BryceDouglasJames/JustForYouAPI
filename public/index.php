<?php
    /*********RESPONSE HEADER CONFIG*********/
    header("Access-Control-Allow-Origin: *");    
    header("Access-Control-Allow-Headers: Content-Type, origin");  

    /*********CONFIG RESOURCES*********/
    include_once '../config/Requests.php';
    include_once '../config/Router.php';
    include_once '../config/Database.php';
    include_once '../config/session.php';

    /*********MODEL CONTROLLERS*********/
    include_once '../src/Controllers/UserController.php';
    include_once '../src/Controllers/QuestionController.php';
    include_once '../src/Controllers/MessageController.php';


    $RequestListener = new Requests();
    $router = new Router($RequestListener);   
    $DBInstance = new Database();
    $con = $DBInstance->establishConn();
    $session = new Session();

    //simple test endpoint
    $router->get('/', function() {
       return("HELLO WORLD :)");
    });

    //simple post test enpoint
    $router->post('/data', function($request) {
        $data = $request->getPayloadData();
        return json_encode($data["username"]);
    });

    //simple post test enpoint
    $router->post('/session/start', function($request) {
        session_start();        
        return json_encode("SESSION STARTED");
    });

    //simple post test enpoint
    $router->post('/session/kill', function($request) {
        session_destroy();        
        return json_encode("SESSION KILLED");
    });
    
    //grab user request, if fields are valid add user
    $router->post("/users/create", function($request) use ($con, $DBInstance){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        $answer = $UserCall -> addUser($data);
        return json_encode($answer);
    });

    //authenticate user for every page they go to
    $router -> post("/users/auth", function($request) use ($con, $DBInstance, $session){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');

        //USE FOR LOGGIN
        //$RequestUser = $UserCall->getCurrent($data);
        //$session->login($RequestUser);

        //CHANGE THIS TO PROPER AUTHENTICATE
        $answer = session::authenticate($data);
        return json_encode($answer);
    });

    $router -> post("/users/login", function($request) use ($con, $DBInstance, $session){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        $answer = session::login($data);
        return json_encode($answer);
    });

    $router -> post("/users/logout", function($request) use ($con, $DBInstance, $session){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        $answer = session::logout($data);
        return json_encode($answer);
    });

    //grab random question/answers and return it to client
    $router -> get("/grab/question", function($request) use ($con, $DBInstance, $session){
        $controller = new QuestionController($con);
        $question = $controller -> getRandomQuestion();
        $question = json_encode($question);
        return $question;
    });


    $router->post('/users/settings/basicinfo', function($request) use ($con, $DBInstance){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('userdata');
        return json_encode($UserCall -> createNewUserInfo($data));
    });

    //FOR ADMIN::::Grabs all users at a rate of 25 by default and sends them back
    $router->post('users/grab/all', function($request) use ($con, $DBInstance){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        return $UserCall->getAllUsers();
        
    });

    //FOR ADMIN::::Grabs and destroys user by username
    $router->post('/users/grab/delete', function($request) use ($con, $DBInstance){
        $data = $request->getPayloadData();
        
        //pass connection to user model to take care of call
        $UserCall = new UserController($con);

        //delete user and return confirmation
        $UserCall -> setCurrentTable('usertable');
        $UserCall->delete($data["DeleteUserWithID"]);
        return $UserCall->getAllUsers();
    });

    $router -> post("/forum/post/create", function($request) use ($con, $DBInstance, $session){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('userposts');
        $messageCall = new MessageController($con);
        $messageCall -> createNewPost($data);
        return json_encode($messageCall);
    });

    $router -> post("/users/set/pfp", function($request) use ($con, $DBInstance, $session){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
    
        $answer = $UserCall->setPFP($data);
        return json_encode($answer);
    });

    $router -> post("/forum/post/update", function($request) use ($con, $DBInstance, $session){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('userposts');
        $messageCall = new MessageController($con);
        $messageCall -> updateCurrentPost($data);
        return json_encode($messageCall);
    });

    $router -> get("/forum/post/getall", function($request) use ($con, $DBInstance, $session){
        $data = $request->getPayloadData();
        $messageCall = new MessageController($con);
        return json_encode($messageCall -> returnAllPosts());
    });

    $router -> post("/forum/post/delete", function($request) use ($con, $DBInstance, $session){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('userposts');
        $messageCall = new MessageController($con);
        $messageCall -> deleteCurrentPost($data);
        return json_encode($messageCall);
    });
?>