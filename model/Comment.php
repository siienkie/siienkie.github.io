<?php

require_once "framework/Model.php";
require_once "Board.php";
require_once "model/Member.php";


class Comment extends Model
{

    public $Body;
    public $Card;
    public $Author;
    public $ID;
    public $CreatedAt;
    public $ModifiedAt;



    public function __construct($Body, $Card, $Author, $ID = null, $CreatedAt = null, $ModifiedAt = null)
    {
        $this->Body = $Body;
        $this->Card = $Card;
        $this->Author = $Author;
        $this->ID = $ID;
        $this->CreatedAt = $CreatedAt;
        $this->ModifiedAt = $ModifiedAt;
    }

    public static function get_comment($ID)
    {
        $query = self::execute("SELECT * FROM comment where id =:id", array("id" => $ID));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();

            return new Comment($row["Body"], $row["Card"], $row["Author"], $row["ID"], $row["CreatedAt"], $row["ModifiedAt"]);
        }
    }
    public static function get_comment_card($Card)
    {
        $query = self::execute("SELECT * FROM comment where Card =:Card  ORDER BY ModifiedAt, CreatedAt Desc", array("Card" => $Card));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();

            return new Comment($row["Body"], $row["Card"], $row["Author"], $row["ID"], $row["CreatedAt"], $row["ModifiedAt"]);
        }
    }

    public static function get_comment_per_card($id_card)
    {
        $query = self::execute("SELECT * from comment where Card = :id_card ORDER BY ModifiedAt, CreatedAt asc", array("id_card" => $id_card));
        $data = $query->fetchAll(); // un seul résultat au maximum
        $comments = [];

        foreach ($data as $row) {
            $comments[] = new Comment($row["Body"],  $row["Card"], $row["Author"], $row["ID"], $row["CreatedAt"], $row["ModifiedAt"]);
        }


        return $comments;
    }

    public static function get_number_comment($id_card)
    {
        $query = self::execute("SELECT count(*) as number from comment where Card = :id_card", array("id_card" => $id_card));
        $row = $query->fetch();
        $count = (int) $row['number'];
        return $count;
    }
    public function validate()
    {
        $errors = array();
        if (!(isset($this->Body) && is_string($this->Body) && strlen($this->Body) > 0)) {
            $errors[] = "Comment must be filled";
        }
        return $errors;
    }

    public function update($user)
    {

        if ($this->ID == null) {
            $member = Member::get_member_by_fullname($user->fullName);



            self::execute('INSERT INTO comment (Body,Author,Card) VALUES (:body,:author,:card)', array('body' => $this->Body, 'author' => $member->id, 'card' => $this->Card));
            $comment = self::get_comment(self::lastInsertId());
            $this->ID = $comment->ID;
            $this->CreatedAt = $comment->CreatedAt;
            return $this;
        } else {
            throw new Exception("Erreur implementation");
        }
    }

    // public function edit($title){
    //     if(self::get_card($this->ID)){
    //         var_dump($this->Body);
    //         self::execute("UPDATE card SET Title=:title, Body=:body WHERE ID=:id",
    //                        array("Title"=>$title, "Body"=>$this->Body, "ID"=>$this->ID));
    //     }
    //     return $this;
    // }

    public static function edit($title, $id_card)
    {
        self::execute(
            "UPDATE card SET Title= :title WHERE ID= :id",
            array("title" => $title, "id" => $id_card)
        );
    }

    public static function edit_comment($body, $id_comment)
    {
        self::execute(
            "UPDATE comment SET Body= :body WHERE ID= :id",
            array("body" => $body, "id" => $id_comment)
        );
    }

    public static function get_date_modified($id_card)
    {
        $card = Card::get_card($id_card);
        if ($card->ModifiedAt == NULL) {
            return "Never Modified";
        } else {
            return $card->ModifiedAt;
        }
    }















    public function delete($id_comment)
    {
        if ($id_comment != null) {
            self::execute('DELETE FROM Comment WHERE id=:id_comment', array('id_comment' => $id_comment));
        }



        //AJOUTER METHODE VALIDATE()
        //AJOUTER METHODE UPDATE()

        //VERIFIER SI L'ID EST NECESSAIRE DANS CE CONSTRUCTEUR







    }
}
