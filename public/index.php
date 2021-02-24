<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");

    include_once '../config/Requests.php';
    include_once '../config/Router.php';
    include_once '../config/Database.php';

    //$Database = new Database("192.168.64.2", "usertest", "roor", "");
    //$Database -> StartConnection();

    $RequestListener = new Requests();
    $router = new Router($RequestListener);
    $Database = new Database();
    $Database->establishConn();

    $router->get('/', function() {
        return <<<HTML
            <h1>Hello world</h1>
            HTML;
    });


    $router->get('/profile', function($request) {
        return <<<HTML
        <h1>Profile</h1>
        HTML;
    });

    $router->post('/data', function($request) {
        
    });

    $router->post('/login', function($request){
        $data = $request->getPayloadData();

        return $data['pass'];
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


    /*
    switch ($method) {
        case 'GET':
            $id = $_GET['id'];
            //if there is an id, request that user. if not return the whole table.
            $sql = "select * from usertest".($id ? " where id=$id" : '');
            break;
        case 'POST':
            $name = $_POST["name"];
            $pass = $_POST["pass"];
            $sql = "insert into user (name, pass) values ('$name', '$pass')"; 
            break;
    }

    $con->close();
    */
    ?>