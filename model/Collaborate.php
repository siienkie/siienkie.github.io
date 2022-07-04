<?php

require_once "framework/Model.php";
require_once "Board.php";
require_once "model/Member.php";


class Collaborate extends Model
{
    public $Board;
    public $Collaborate;

    public function __construct($Board, $Collaborate)
    {
        $this->Board = $Board;
        $this->Collaborate = $Collaborate;
    }

    public static function get_collaborate_by_id_board($id_board)
    {
        $query = self::execute("SELECT user.Mail, user.Password, user.FullName, user.Role, user.ID, user.RegisteredAt FROM `collaborate` 
                                JOIN `user` on collaborate.collaborator = user.ID WHERE collaborate.Board=:id_board", array("id_board" => $id_board));
        if ($query->rowCount() == 0) {
            return false;
        }
        $data = $query->fetchAll();
        $Collaborate = [];

        foreach ($data as $row) {
            $Collaborate[] = new Member($row["Mail"], $row["Password"], $row["FullName"], $row["Role"], $row["ID"], $row["RegisteredAt"]);
        }
        return $Collaborate;
    }

    public static function get_board_from_collaborate($id_colla){
    $query = self::execute('SELECT Board FROM Collaborate WHERE Collaborator = :id_colla',array("id_colla"=>$id_colla));
    if ($query->rowCount() == 0) {
        return false;
    } else {
        $row = $query->fetch();

        return $row;
    }
}
}
