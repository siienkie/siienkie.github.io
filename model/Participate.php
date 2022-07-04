<?php

require_once "framework/Model.php";
require_once "Board.php";
require_once "model/Member.php";


class Participate extends Model
{
    public $Participant;
    public $Card;

    public function __construct($Participant, $Card)
    {
        $this->Participant = $Participant;
        $this->Card = $Card;
    }


    public static function get_participant($id_card)
    {
        $query = self::execute("SELECT user.Mail, user.Password, user.FullName, user.Role ,user.ID, user.RegisteredAt FROM `participate` JOIN user on 
                                participate.Participant = user.ID WHERE Card=:id_card", array("id_card" => $id_card));
        if ($query->rowCount() == 0) {
            return false;
        }
        $data = $query->fetchAll();
        $Participant = [];

        foreach ($data as $row) {
            $Participant[] = new Member($row["Mail"], $row["Password"], $row["FullName"], $row["Role"], $row["ID"], $row["RegisteredAt"]);
        }
        return $Participant;
    }

    public static function get_one_participant($id_card)
    {
        $query = self::execute("SELECT user.Mail, user.Password, user.FullName, user.ID, user.RegisteredAt FROM `participate` JOIN user on participate.Participant = user.ID WHERE Card=:id_card", array("id_card" => $id_card));
        if ($query->rowCount() == 0) {
            return false;
        }
        $row = $query->fetchAll();



        return new Member($row["Mail"], $row["Password"], $row["FullName"], $row["Role"], $row["ID"], $row["RegisteredAt"]);
    }

    public static function delete($id_collaborateur, $id_card)
    {
        self::execute('DELETE FROM participate WHERE Participant=:id_collaborateur and Card=:id_card', array('id_collaborateur' => $id_collaborateur, "id_card" => $id_card));
    }
    public static function delete_all_card($id_card)
    {
        self::execute('DELETE FROM participate WHERE Card=:id_card', array("id_card" => $id_card));
    }

}
