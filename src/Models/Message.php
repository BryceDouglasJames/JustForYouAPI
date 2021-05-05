<?php

require_once __DIR__ . '/Model.php';

class Message extends Model
{
    public $id;
    public $title;
    public $body;
    public $created_at;
    
    public function __construct()
    {
        parent::__construct();
    }


    /*
    *   TECHNICALLY AN INTERFACE FOR USER POST REQUEST THAT HANDLES RECORD INSERTION
    */
    public function createPost($uid, $content){
        $cols = array("UID", "CID", "title", "author", "likes", "image", "body", "created_at", "updated_at");
        $vals = $content;
        return self::insert($cols, $vals);
    }

    /*
    *   GRABS POST BY ID AND RETURNS POST OBJECT FOR HYDRATION ON FRONT END
    */
    public function getPost($id){
        $answer = array();
        $id = self::cleanSQL(self::$conn, $id);
        $sql = "SELECT * FROM " . self::getTable() . " WHERE POSTID = '" . $id . "'";
        $result = self::query($sql);
        if(!$result){
            return false;
        }else{
            while($row = $result->fetch_row()){
                $buffer = array(
                    'POSTID' => $row[0],
                    'UID' => $row[1],
                    'CID' => $row[2],
                    'title' => $row[3],
                    'author' => $row[4],
                    'likes' => $row[5],
                    'image' => $row[6],
                    'category' => $row[7],
                    'created_at' => $row[8],
                    'updated_at' => $row[9]
                ); 
                array_push($answer, $buffer);    
            }
            return $answer;
        }
    }

    /*
    *   COMES AFTER USER AUTHENTIATION
    *   ALIGNES KEYS AND POSHES VALUES TO BE STORED IN DB
    */
    public function updatePost($payload, $post){
        $cols = array();
        $vals = array();
        foreach ($payload as $key => $value) {
            if($key == "username" || $key == "post_id" || $key == "UID" || $key == "likes" || $key == "created_at"|| $value = null || $key == "category" || $key =="author"){
                continue;
            }else if($key == "body" || $key == "title" || $key == "image" ){
                $temp = "'".$payload[$key]."'";
                array_push($cols, $key);
                array_push($vals, $temp);
            }else{
                throw new error("unexpected values for updating post.");
            }
        }
        array_push($cols, "updated_at");
        array_push($vals, "'".$formattedTime."'");
        return Message::update($cols, $vals, $payload["post_id"]);
    }


    /*
    *   SIMPLY GRABS POST BY ID AND DELETES RECORD
    */
    public function deletePost($post){
        $sql = 'DELETE FROM userposts WHERE POSTID= ' . $post[0]["POSTID"];
        $entries = self::query($sql);
        return $entries;
    }

    /*
    *   GRABS POST AND INCREMENTS LIKE VALUE
    */
    public function likePost($id){
        $post = self::getPost($id);
        $newLikeAmount = $post[0]["likes"] + 1;
        return self::update(array("likes"), array($newLikeAmount), $id);
    }
    
    /*
    *   STRUCTURES QUERY TO RECIEVE FIRST 100 POST RESULTS
    */
    public function getAllPosts(){
        $sql = 'SELECT * FROM userposts LIMIT 100';
        $entries = self::query($sql);
        return $entries;
    }
}
