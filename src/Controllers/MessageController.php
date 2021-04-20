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

    public function createNewPost($payload){
        $current = date_create();
        $formattedTime = date_format($current, 'Y-m-d H:i:s');
        $UID = User::getID($payload["username"]);
        $title = $payload["title"];
        $author = $payload["username"];
        $category = $payload["category"];
        $body = $payload["body"];
        $image = null;
        if($payload["image"]){
            $image = $payload["MessageImage"];
        }

        $valueArray = array($UID, $category, $title, $author, 0, $image, $body, $formattedTime, $formattedTime);
        return Message::createPost($UID, $valueArray);
    }

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
}

?>