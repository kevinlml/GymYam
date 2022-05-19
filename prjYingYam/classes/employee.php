
<?php 

if(!class_exists('PERSON')){ include "person.php"; }

class EMPLOYEE extends PERSON{
	
    private $preferred_shedule;
    
    public function GetID(){
        return $this->person_id;
    }
    
    function __construct( $name , $email, $phone, USER $user, $preferred_shedule){
        
	   PERSON::__construct( $name , $email, $phone, $user);
        
        $this->preferred_shedule = $preferred_shedule;
           
    }
    
	
	public function Create(){
        
        //Create user account first 
        $this->user->Create(); 
        
        
        //creating entry on student table 
		$query = "INSERT INTO employee ( name, email, phone, preferred_schedule, username) ";
		$query .= "VALUES(?,?,?,?,?)";
		PERSON::Init_Database();
		
		try{
			$sql = PERSON::$database->Connection->prepare($query);
            $sql->bindParam(1, $this->name);
            $sql->bindParam(2, $this->email);
            $sql->bindParam(3, $this->phone); 
            $sql->bindParam(4, $this->preferred_shedule);
            $sql->bindParam(5, $this->user->GetUsername()); 
			
			$sql->execute();
            
            $last_id = self::$database->Connection->LastInsertId();
			
			return $last_id;
			
		}catch(PDOException $e){
			echo "Query INSERT Failed ".$e->getMessage();
		}
	}
	
	
	public function Delete($employee_id){
		
        $query  = "DELETE FROM employee  ";
		$query .= "WHERE employee_id = $employee_id";
		PERSON::Init_Database();
		try{
			PERSON::$database->Connection->exec($query);
            USER::Delete(self::Get_Username($employee_id));
			return true;
		}catch(PDOException $e){
			echo "Query UPDATE Failed ".$e->getMessage();
			return false;
		}
	}
    
     public static function Get_Username($employee_id){
		self::init_database();
		$connection = self::$database->Connection;
		try{
			$query = "SELECT username FROM employee WHERE employee_id = $employee_id ";
			$stmt = $connection->prepare($query);
			$stmt->execute();
			$userObj = $stmt->fetch(PDO::FETCH_OBJ);
			
			return $userObj->salt;
			
		}catch(PDOException $e){
			echo "Query Failed ".$e->getMessage();
		}
	}
  
 
    
    public static function ListAll(){
        
		PERSON::Init_Database();
        
		$connection = self::$database->Connection;
		$query  = "SELECT * FROM employee ";
		
		try{
			$stmt = $connection->prepare($query);
			$stmt->execute();
			
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
			
		}catch(PDOException $e)
		{
			echo "Query Read students Failed: ".$e->getMessage();
		}
    }
    
    
    public static function Display($array){
       
      
        echo "<table>";
        
        echo "<th>Employee ID</th>";
        echo "<th>Name</th>";
        echo "<th>Email</th>";
        echo "<th>Phone</th>";
        echo "<th>Preferred Schedule</th>";
        echo "<th>UserName</th>";
        
	foreach($array as $Index => $Element){
		echo "<tr>";
			echo "<td>";
			echo $Element['employee_id'];
			echo "</td>";
							
			echo "<td>";
			echo "<h2 style=\"color:#CC0000;\">";
			echo $Element['name'];
			echo "</h2>";
            echo "</td>";
                
			echo "<td>";
			echo "<h2 style=\"color:#121111;\">";
			echo $Element['email'];
			echo "</h2>";
            echo "</td>";
                
                
			echo "<td>";
			echo "<h2 style=\"color:#121111;\">";
			echo $Element['phone'];
			echo "</h2>";
            echo "</td>";
                
			echo "<td>";
			echo "<h2 style=\"color:#121111;\">";
			echo $Element['preferred_schedule'];
			echo "</h2>";
            echo "</td>";
                    
			echo "<td>";
			echo "<h2 style=\"color:#121111;\">";
			echo $Element['username'];
			echo "</h2>";
            echo "</td>";
			echo "</tr>";
	}
        
        echo "</table>";
        
        
}
    
    
    
public static function ListEmployeeCourses($employe_id){
        
		PERSON::Init_Database();
        
		$connection = self::$database->Connection;
		$query  = "SELECT employee.employee_id, course.course_id, course.name, course.start_date, course.end_date, course.schedule, course.price, course.employee_id AS Expr1
FROM     employee INNER JOIN
                  course ON employee.employee_id = course.employee_id
WHERE  employee.employee_id = 1";
		try{
			$stmt = $connection->prepare($query);
			$stmt->execute();
			
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
			
		}catch(PDOException $e)
		{
			echo "Query Read teacher course Failed: ".$e->getMessage();
		}
    }
    
        public static function DisplayEmplpoyeeCourses($array){
       
      
        echo "<table style='border-spacing: 4px; border-collapse:separate; color:white'>";
        
        echo "<th>Course ID</th>";
        echo "<th>Name</th>";
        echo "<th>Start Date</th>";
        echo "<th>End Date</th>";
        echo "<th>Schedule</th>";
        
        
	foreach($array as $Index => $Element){
		echo "<tr>";
			echo "<td>";
			echo $Element['course_id'];
			echo "</td>";
							
			echo "<td>";
			echo "<h2 >";
			echo $Element['name'];
			echo "</h2>";
            echo "</td>";
                
			echo "<td>";
			echo "<h2 >";
			echo $Element['start_date'];
			echo "</h2>";
            echo "</td>";
                
                
			echo "<td>";
			echo "<h2 >";
			echo $Element['end_date'];
			echo "</h2>";
            echo "</td>";
                    
			echo "<td>";
			echo "<h2 >";
			echo $Element['schedule'];
			echo "</h2>";
            echo "</td>";
                
			echo "</tr>";
	}
        
        echo "</table>";
    }
    
    
    public static function AddCancelation($course_id, $date){
        
          //creating entry on course table 
		$query = "INSERT INTO cancelation ";
		$query .= "VALUES(?, ?)";
		PERSON::Init_Database();
		
		try{
			$sql = PERSON::$database->Connection->prepare($query);
            $sql->bindParam(1, $course_id);
            $sql->bindParam(2, $date);
			$sql->execute();
            
			
		}catch(PDOException $e){
			echo "Query INSERT Failed ".$e->getMessage();
		}
    }
    
    public static function Get_Employee_Name($employe_id) {
	
    $employeName = 'teacher';
    
		self::Init_Database();
        
    $connection = self::$database->Connection;
    $query = "SELECT employee.employee_id, `user`.username
FROM     employee INNER JOIN
                  `user` ON employee.username = `user`.username
WHERE  `user`.username = $employe_id";
    
		try{
			$stmt = $connection->prepare($query);
			$stmt->execute();
			$userObj = $stmt->fetch(PDO::FETCH_OBJ);
			
            $teacherName = $userObj->name;
            return $employeName;
			
		}catch(PDOException $e){
			echo "Query Failed ".$e->getMessage();
		}
        
}   
    
    public static function Get_EmployeeID($username){
		self::init_database();
		$connection = self::$database->Connection;
		try{
			$query = "SELECT employee.employee_id, `user`.username
FROM     employee INNER JOIN
                  `user` ON employee.username = `user`.username
WHERE  `user`.username = $username ";
			$stmt = $connection->prepare($query);
			$stmt->execute();
			$userObj = $stmt->fetch(PDO::FETCH_OBJ);
			
			return $userObj->employee_id;
			
		}catch(PDOException $e){
			echo "Query Failed ".$e->getMessage();
		}
	}
   
    
    
}

?>