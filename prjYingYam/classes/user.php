
<?php 
if(!class_exists('DatabasePDO')){ include "database.php"; }


class USER{
	
	private $username;
    private $password;
    private $user_type;
    private $salt;
    
	private static $database;
	
	function __construct($username , $password, $user_type, $salt= null )
    {
        
        $this->username = $username;
        $this->password = $password;
        $this->user_type = $user_type;
        $this->salt = $salt;
	}
	public static function Init_Database(){
		if(! isset(self::$database)){
			self::$database = new DatabasePDO();
		}
	}
	
    public function GetUsername(){
        return $this->username;
    }
    public function GetPassword(){
        return $this->password;
    }
	
	
	public function Create(){
        
		$this->salt = self::Create_Salt($this->password);
		$this->password = crypt($this->password, $this->salt);
        
		$query = "INSERT INTO user(username, password, user_type, salt) ";
		$query .= "VALUES(?,?,?,?)";
		self::Init_Database();
		
		try{
			$sql = self::$database->Connection->prepare($query);
            $sql->bindParam(1, $this->username);
            $sql->bindParam(2, $this->password);
            $sql->bindParam(3, $this->user_type); 
            $sql->bindParam(4, $this->salt); 
           
			
			$sql->execute();
            
            return true;
			
		}catch(PDOException $e){
			echo "Query INSERT Failed ".$e->getMessage();
		}
	}
	
	public static function Login($username, $password){
        echo "Login";
        self::Init_Database();
        
		$salt = self::Get_Salt($username);
		$encrypted = crypt($password , $salt);
        
        echo "salt ".$salt."<br>";
        echo "crypted ".$encrypted."<br>";
		$query  = "SELECT * FROM `user` ";
        $query .= "WHERE `username` = '$username' "; 
		
		try{
			$sql = self::$database->Connection->prepare($query);
			$sql->execute();
			$result = $sql->fetch(PDO::FETCH_OBJ);
            
			return $result->user_type;
			
		}catch(PDOException $e){
			echo "Query SELECT Failed ".$e->getMessage();
		}
	
    }

    public static function Create_Salt($password){
         
        
        $random = MD5($password);
		$salt = Substr($random, 0, 22);
		$hash = '$2y$10$';
		return $hash.$salt."$";
    }
    
    public static function Get_Salt($username){
		self::init_database();
		$connection = self::$database->Connection;
		try{
			$query = "SELECT * FROM user WHERE username = '$username' ";
			$stmt = $connection->prepare($query);
			$stmt->execute();
			$userObj = $stmt->fetch(PDO::FETCH_OBJ);
			
			return $userObj->salt;
			
		}catch(PDOException $e){
			echo "Query Failed ".$e->getMessage();
		}
	}
  
	public static function Delete($username){
		
		
        $query  = "DELETE FROM user  ";
		$query .= "WHERE username = '$username'";
		self::Init_Database();
		try{
			self::$database->Connection->exec($query);
			return true;
		}catch(PDOException $e){
			echo "Query UPDATE Failed ".$e->getMessage();
			return false;
		}
	}
    
    public static function Exists($username){
		$query = "SELECT username FROM user WHERE username = '$username' ";
		self::Init_Database();
		try{
			$sql = self::$database->Connection->prepare($query);
			$sql->execute();
			$result = $sql->fetch(PDO::FETCH_OBJ);
			
			return !empty($result->username);
		}catch(PDOException $e){
			echo "Query SELECT Failed ".$e->getMessage();
		}
	}
    
    	public static function Update_Password($username , $new_password){
		self::Init_Database();
		$connection = self::$database->Connection;
		
		$salt = self::Create_Salt($new_password);
		$new_encrypted = crypt($new_password, $salt);
		try{
			$connection->beginTransaction();
			$query = "Update user Set password = '$new_encrypted' WHERE username = '$username' ";
			$connection->exec($query);
			$query = "Update user Set salt = '$salt' WHERE username = '$username' ";
			$connection->exec($query);
			$connection->commit();
		}catch(PDOException $e){
			echo "Query Update Failed ".$e->getMessage();
			$connection->rollback();
		}
	}
}
?>