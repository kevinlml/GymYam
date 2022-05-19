<?php

include 'classes/dbConfig.php';

class DatabasePDO{
    
    public $Connection;
    
    function __construct(){
        
        $this->OpenConnection();
    }
    function __destruct(){
        
        $this->CloseConnection();
    }
    function OpenConnection(){
        global $HOST , $USER , $PASSWORD , $DB_NAME;
        
        try{
            $this->Connection =  new PDO("mysql:host=$HOST;dbname=$DB_NAME" ,
                $USER , $PASSWORD);
            $this->Connection->setAttribute(PDO::ATTR_ERRMODE ,
                PDO::ERRMODE_EXCEPTION);
            
        }catch(PDOException $e){
            echo "Connection Failed ".$e->getMessage();
        }
        
    }
    function CloseConnection(){
        $this->Connection = null;
    }
}

?>