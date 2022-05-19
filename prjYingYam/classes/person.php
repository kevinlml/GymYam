<?php

if(!class_exists('DatabasePDO')){ include "database.php"; }
if(!class_exists('USER')){ include "user.php"; }


abstract class PERSON {
    
    protected  $person_id;
    protected  $name;
    protected  $email;
    protected  $phone;
    protected  $user;
    
    protected static $database;
    
    function __construct( $name , $email, $phone, USER $user, $person_id = null)
    {
        
        $this->person_id = $person_id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->user = $user;
    }
    
    final public function Init_Database(){
        if(! isset(self::$database)){
            self::$database = new DatabasePDO();
        }
    }
    
    
    
    public function Create(){}
    
    public function Update(){}
    
    public function Delete($person_id){}
    
    
}
?>