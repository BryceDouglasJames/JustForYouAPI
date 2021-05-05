<?php

include_once __DIR__ .'/../Models/User.php';
include_once __DIR__ .'/../Models/Message.php';
include_once __DIR__ .'/../../config/session.php';


class MessageController
{
    protected $db;

    public function __construct($con){
        Message::useConnection($con);
    }


    /*
    *  CREATE POST TIMESTAMP, FORMAT PAYLOAD DATA AND INSERT POST INTO DB
    */
    public function createNewPost($payload){
        $current = date_create();
        $formattedTime = date_format($current, 'Y-m-d H:i:s');
        $UID = User::getID($payload["username"]);
        $title = $payload["title"];
        $author = $payload["username"];
        $category = $payload["category"];
        $body = $payload["body"];
        $image = null;
        
        $valueArray = array($UID, $category, $title, $author, 0, $image, $body, $formattedTime, $formattedTime);
        return Message::createPost($UID, $valueArray);
    }

    /*
    *  CREATE UPDATE TIMESTAMP, AUTHENTICATE USER PRIVILAGE, 
    *  EXECUTE UPDATE IF USER
    */
    public function updateCurrentPost($payload){
        $current = date_create();
        $formattedTime = date_format($current, 'Y-m-d H:i:s');
        $UID = User::getID($payload["username"]);
        $post = Message::getPost($payload["post_id"]);

        if($post[0]["UID"] == $UID){
            return Message::updatePost($payload, $post);
        }else{
            throw new error("This user does not have privilage to change post.");
        }
    }

    public function likeCurrentPost($payload){
        return Message::likePost($payload["post_id"]);
    }


    /*
    * CREATE DELETION TIMESTAMP, CHECK TO SEE IF ADMIN OR OP MADE REQUEST, 
    * IF SO, DELETE POST FROM DB 
    */
    public function deleteCurrentPost($payload){
        $current = date_create();
        $formattedTime = date_format($current, 'Y-m-d H:i:s');
        $UID = User::getID($payload["username"]);
        $post = Message::getPost($payload["post_id"]);
        if($post[0]["UID"] == $UID || $payload["username"] == "JUST4YOUMOD"){
            return Message::deletePost($post);
        }else{
            throw new error("User does not have permisson to delete this post.");
        }
    }

    /*
    *   GRAB ALL POSTS WITHIN THE FIRST 100 RESULTS (FOR NOW)
    *   RETURN OBJECT MAP OF POST TO BE HYDRATED 
    */
    public function returnAllPosts(){
        $returnArray = array();
        $sql = 'SELECT * FROM userposts LIMIT 100';
        $result = Message::query($sql);        
        while($row = $result->fetch_row()){
            $buffer = array(
                'POSTID' => $row[0],
                'UID' => $row[1],
                'CID' => $row[2],
                'title' => $row[3],
                'author' => $row[4],
                'likes' => $row[5],
                'image' => $row[6],
                'body' =>  $row[7],
                'created_at' => $row[8]
            );                   
            array_push($returnArray, $buffer);
        }
        return $returnArray;
    }
}

?>