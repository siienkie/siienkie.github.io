<?php
require_once "framework/Model.php";
require_once "model/Member.php";
    class Profile extends Model{

        public $ID;
        public $title;
        public $owner;
        public $created;
        public $modified;

        public function __construct($ID,$title,$owner,$created,$modified){
            $this->ID = $ID;
            $this->title = $title;
            $this->owner = $owner;
            $this->created = $created;
            $this->modified = $modified;
        }
        public static function get_title_board($user){
            $owner=$user->mail;
            $query = self::execute("SELECT board.ID,board.Title,board.Owner,board.CreatedAt,board.ModifiedAt from board,user where user.id = board.owner AND mail = :pers",array("pers"=>$owner));
            $data = $query->fetchAll();
            $results=[];
            foreach($data as $row){
          
                $results[]= new Profile($row["ID"],$row["Title"], $row["Owner"], $row["CreatedAt"], $row["ModifiedAt"]);
            }
            return $results;
        }


        public static function validate_title_board($title){
            
            $query = self::execute("SELECT * from board where title= :title", array("title"=>$title));
            $data = $query->fetchAll();

            if($data == 0){
                return true; //vrai si existe pas
            }
            return false; //faux si existe
        }

        public static function validate_title_board2($title){
            $errors = [];

            if($title == null){
                $errors[] = "le titre ne peut pas etre vide";
            }
            if(Profile::validate_title_board($title)){
                $errors[] = "ce titre existe deja";
            }

            return $errors;
        }
    
    

       public static function add_board($title,$user){
        $fullname = $user->fullName;
        $member = Member::get_member_by_fullname($fullname);

        if(Board::validate_title_caractere($title) == [] && Board::validate_title($title) == []){
            $query = self::execute("INSERT INTO board (title,owner) VALUES (:title,:owner)",
            array("title"=>$title,"owner"=>$member->id));
        }
        

        
       }
      
    public static function other_board($user){
        $owner = $user->mail;
        $query = self::execute("SELECT * from board,user where user.id = board.owner AND NOT mail  = :pers",array("pers"=>$owner));
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] =  new Profile($row["ID"],$row["Title"], $row["FullName"], $row["CreatedAt"], $row["ModifiedAt"]);
        }
        return $results;

       }
       public static function delete_board($ID){
           $query = self::execute("DELETE FROM board WHERE id = :id ",
            array("id"=>$ID));
       } 
    }
