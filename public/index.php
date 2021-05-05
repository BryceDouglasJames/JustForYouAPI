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
    include_once '../src/Controllers/MLAssistantController.php';


    /*
    *   INITIALIZE REQUEST INSTANCE OBJECTS AND CONNECT TO DB
    */
    $RequestListener = new Requests();
    $router = new Router($RequestListener);   
    $DBInstance = new Database();
    $con = $DBInstance->establishConn();
    $session = new Session();



    /*
    *   EVERY ENDPOINT FUCNTION STARTS RELATIVELY THE SAME.
    *   INGEST THE PAYLOAD DATA, PARSE OBJECT KEYS AND INSTANCE A CONTROLLER
    *   CONTROLLER IS RESPONSIBLE FOR HANDLING INHERITED METHODS OF ABSTRACT ENDPOINT MODEL
    */

    /*
    *   INDEX TEST ENDPOINT
    */
    $router->get('/', function() {
       return("HELLO WORLD :)");
    });


    /*******************************ADMIN ENDPOINTS****************************************/
    //FOR ADMIN::::Grabs all users at a rate of 25 by default and sends them back
    $router->post('users/grab/all', function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        return $UserCall->getAllUsers();
        
    });

    //FOR ADMIN::::Grabs and destroys user by username
    $router->post('/users/grab/delete', function($request) use ($con){
        $data = $request->getPayloadData();
        
        //pass connection to user model to take care of call
        $UserCall = new UserController($con);

        //delete user and return confirmation
        $UserCall -> setCurrentTable('usertable');
        $UserCall->delete($data["DeleteUserWithID"]);
        return $UserCall->getAllUsers();
    });

    /****************************************************************************/

    
    /*************************USER SESSION ENDPOINTS***************************************/

    //authenticate user for every page they go to
    $router -> post("/users/auth", function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');

        //CHANGE THIS TO PROPER AUTHENTICATE
        $answer = session::authenticate($data);
        return json_encode($answer);
    });

    //update user login and return them an answer for session instantiation
    $router -> post("/users/login", function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        $answer = session::login($data);
        return json_encode($answer);
    });

    //kill session and log user out
    $router -> post("/users/logout", function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        $answer = session::logout($data);
        return json_encode($answer);
    });


    /****************************************************************************/



    /***************************USER PROFILE ENDPOINTS********************/

    //grab user request, if fields are valid add user
    $router->post("/users/create", function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        $answer = $UserCall -> addUser($data);
        return json_encode($answer);
    });

    
    //upon request, authenticate new user and create recoirds for them in the DB
    $router->post('/users/settings/basicinfo', function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('userdata');
        return json_encode($UserCall -> createNewUserInfo($data));
    });

    /*
    *   TODO
    *   AFTER MIGRATION, THIS ENPOINT WILL HANLE USER PROFILE PIC STORAGE
    */
    $router -> post("/users/set/pfp", function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        $answer = $UserCall->setPFP($data);
        return json_encode($answer);
    });
    /****************************************************************************/


    /**************************QUESTION RESPOSE ENDPOINTS**********************************/

    //grab random question/answers and return it to client
    $router -> get("/grab/question", function($request) use ($con){
        $controller = new QuestionController($con);
        $question = $controller -> getRandomQuestion();
        $question = json_encode($question);
        return $question;
    });

    //grab random question/answers and return it to client
    $router -> post("/grab/question/category", function($request) use ($con){
        $data = $request->getPayloadData();
        $controller = new QuestionController($con);
        $question = $controller -> questionByCategory($data);
        $question = json_encode($question);
        return $question;
    });

    //grab random question/answers and return it to client
    $router -> post("/grab/question/answered", function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('usertable');
        $controller = new QuestionController($con);
        $question = $controller -> answerQuestion($data);
        $MLCall = new MLAssistantController($con);
        return json_encode($MLCall -> updateScore($data));
    });

    /****************************************************************************/

    
    /******************************FORUM ENDPOINTS************************************/
    //returns all user posts to be hydrated
    $router -> get("/forum/post/getall", function($request) use ($con){
        $data = $request->getPayloadData();
        $messageCall = new MessageController($con);
        return json_encode($messageCall -> returnAllPosts());
    });

    //triggered when user likes a post, handle update
    $router -> post("/forum/post/like", function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('userposts');
        $messageCall = new MessageController($con);
        $messageCall -> likeCurrentPost($data);
        return json_encode($messageCall);
    });
    /****************************************************************************/


    /******************************USER POST PROPERTY ENDPOINTS************************************/
    /*****************************************CRUD*************************************************/

    //CREATE POST
    $router -> post("/forum/post/create", function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('userposts');
        $messageCall = new MessageController($con);
        $messageCall -> createNewPost($data);
        return json_encode($messageCall);
    });

    //UPDATE POST
    $router -> post("/forum/post/update", function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('userposts');
        $messageCall = new MessageController($con);
        $messageCall -> updateCurrentPost($data);
        return json_encode($messageCall);
    });

    //DELETE POST
    $router -> post("/forum/post/delete", function($request) use ($con){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $UserCall -> setCurrentTable('userposts');
        $messageCall = new MessageController($con);
        $messageCall -> deleteCurrentPost($data);
        return json_encode($messageCall);
    });
    /***************************************************************************************/
    

    /**************************************ML ASSISTANT ENDPOINTS******************************************/
    //When a user answers a question, the ML assitant will record the response and 
    //trigger a training instance on user to update their score
    $router -> post("/user/updateScore", function($request) use ($con, $DBInstance, $session){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $messageCall = new MLAssistantController($con);
        return json_encode($messageCall -> updateScore($data));
    });

    //returns all user scores thoroughout the week
    $router -> post("/testing/scores", function($request) use ($con, $DBInstance, $session){
        $data = $request->getPayloadData();
        $UserCall = new UserController($con);
        $messageCall = new MLAssistantController($con);
        return json_encode($messageCall -> getUserScore($data));
        //return $messageCall -> train();
    });
    /***************************************************************************************/

?>