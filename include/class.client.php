<?php
/*********************************************************************
    class.client.php
**********************************************************************/

class Client {


    var $id;
    var $fullname;
    var $username;
    var $passwd;
    var $email;

    
    var $udata;
    var $ticket_id;
    var $ticketID;

    function Client($email,$id){
        $this->id =0;
        return ($this->lookup($id,$email));
    }

    function isClient(){
        return TRUE;
    }

    function lookup($id,$email=''){
        $sql='SELECT ticket_id,ticketID,name,email FROM '.TICKET_TABLE.' WHERE ticketID='.db_input($id);
        if($email){ //don't validate...using whatever is entered.
            $sql.=' AND email='.db_input($email);
        }
        $res=db_query($sql);
        if(!$res || !db_num_rows($res))
            return NULL;

        /* Faking most of the stuff for now till we start using accounts.*/
        $row=db_fetch_array($res);
        $this->udata=$row;
        $this->id         = $row['ticketID']; //placeholder
        $this->ticket_id  = $row['ticket_id'];
        $this->ticketID   = $row['ticketID'];
        $this->fullname   = ucfirst($row['name']);
        $this->username   = $row['email'];
        $this->email      = $row['email.inamujer.gob.ve'];
      
        return($this->id);
    }


    function getId(){
        return $this->id;
    }

    function getEmail(){
        return($this->email);
    }

    function getUserName(){
        return($this->username);
    }

    function getName(){
        return($this->fullname);
    }
    
    function getTicketID() {
        return $this->ticketID;
    }
}

?>
