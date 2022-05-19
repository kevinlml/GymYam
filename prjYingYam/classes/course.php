<?php


if(!class_exists('DatabasePDO')){ include "database.php"; }

if(!class_exists('EMPLOYEE')){ include "employee.php"; }


class COURSE {
    
    private $course_id;
    private $name;
    private $start_date;
    private $end_date;
    private $schedule;
    private $price;
    private $employee;
    
    
    
    protected static $database;
    
    function __construct( $name , $start_date, $end_date, $schedule, $price,   $employee, $course_id = null)
    {
        
        $this->course_id = $course_id;
        $this->name = $name;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->schedule = $schedule;
        $this->price = $price;
        $this->employee = $employee;
    }
    
    final public function Init_Database(){
        if(! isset(self::$database)){
            self::$database = new DatabasePDO();
        }
    }
    
    
    
    public function Create(){
        
        //creating entry on course table
        $query = "INSERT INTO course ( name, start_date, end_date, schedule, price, employee_id )";
        $query .= "VALUES(?,?,?,?,?, ?)";
        self::Init_Database();
        
        try{
            $sql = self::$database->Connection->prepare($query);
            $sql->bindParam(1, $this->name);
            $sql->bindParam(2, $this->start_date);
            $sql->bindParam(3, $this->end_date);
            $sql->bindParam(4, $this->schedule);
            $sql->bindParam(5, $this->price);
            $sql->bindParam(6, $this->employee);
            $sql->execute();
            
            $last_id = self::$database->Connection->LastInsertId();
            
            return $last_id;
            
        }catch(PDOException $e){
            echo "Query INSERT Failed ".$e->getMessage();
        }
    }
    
    public function Update($course_id){
        
        $query = "UPDATE course ";
        $query .= "SET name = ? , start_date = ? , end_date = ?, schedule = ?, price = ? , employee_id = ? ";
        $query .= "WHERE course_id = $course_id ";
        
        
        PERSON::Init_Database();
        
        try{
            $sql = PERSON::$database->Connection->prepare($query);
            $sql->bindParam(1, $this->name);
            $sql->bindParam(2, $this->start_date);
            $sql->bindParam(3, $this->end_date);
            $sql->bindParam(4, $this->schedule);
            $sql->bindParam(5, $this->price);
            $sql->bindParam(6, $this->employee);
            
            $sql->execute();
            
            
        }catch(PDOException $e){
            echo "Query UPDATE Failed ".$e->getMessage();
        }
        
    }
    
    public static function Delete($course_id){
        
        
        $query  = "DELETE FROM course  ";
        $query .= "WHERE course_id = $course_id";
        self::Init_Database();
        try{
            self::$database->Connection->exec($query);
            return true;
        }catch(PDOException $e){
            echo "Query UPDATE Failed ".$e->getMessage();
            return false;
        }
    }
    
    public static function ListAll(){
        
        self::Init_Database();
        
        $connection = self::$database->Connection;
        $query  = "SELECT * FROM course ";
        
        try{
            $stmt = $connection->prepare($query);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            
        }catch(PDOException $e)
        {
            echo "Query Read courses Failed: ".$e->getMessage();
        }
    }
    
    
    public static  function Display($array){
        
        
        echo "<table  border=0 width='750'>";
        
        echo "<th>Course ID</th>";
        echo "<th>Name</th>";
        echo "<th>start_date</th>";
        echo "<th>end_date</th>";
        echo "<th>schedule</th>";
        echo "<th>price</th>";
        echo "<th>employee_id</th>";
        
        
        foreach($array as $Index => $Element){
            echo "<tr>";
            echo "<td>";
            echo $Element['course_id'];
            echo "</td>";
            
            echo "<td>";
            echo "<h2>";
            echo $Element['name'];
            echo "</h2>";
            echo "</td>";
            
            echo "<td>";
            echo "<h2 >";
            echo $Element['start_date'];
            echo "</h2>";
            echo "</td>";
            
            
            echo "<td>";
            echo "<h2>";
            echo $Element['end_date'];
            echo "</h2>";
            echo "</td>";
            
            echo "<td>";
            echo "<h2>";
            echo $Element['schedule'];
            echo "</h2>";
            echo "</td>";
            
            echo "<td>";
            echo "<h2 >";
            echo $Element['price'];
            echo "</h2>";
            echo "</td>";
            
            echo "<td>";
            echo "<h2 >";
            echo $Element['employee_id'];
            echo "</h2>";
            echo "</td>";
            
            echo "</tr>";
        }
        
        echo "</table>";
    }
    
    
    
    
    public static function Get_Course_Name($course_id) {
        //TODO ...
        $courseName = 'Course';
        
        self::Init_Database();
        
        $connection = self::$database->Connection;
        $query = "SELECT * FROM course WHERE course_id = $course_id ;";
        
        try{
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $userObj = $stmt->fetch(PDO::FETCH_OBJ);
            $courseName = $userObj->name;
            
        }catch(PDOException $e){
            echo "Query Failed ".$e->getMessage();
        }
        
        return $courseName;
    }
    
    
    
    public static function GetAllCancelations(){
        self::Init_Database();
        
        $connection = self::$database->Connection;
        $query  = "SELECT c.course_id, c.name, c.schedule, c.price, c.employee_id, can.date ";
        $query .= "FROM cancelation can, course c";
        $query .= "WHERE c.course_id = can.course_id";
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
    
    
    public static function DisplayAllCancelations($array){
        
        
        
        echo "<table  border=0 width='750' >";
        
        echo "<th>Course ID</th>";
        echo "<th>Name</th>";
        echo "<th>schedule</th>";
        echo "<th>price</th>";
        echo "<th>employee_id</th>";
        echo "<th>Cancelation Date</th>";
        
        
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
            echo $Element['schedule'];
            echo "</h2>";
            echo "</td>";
            
            echo "<td>";
            echo "<h2 >";
            echo $Element['price'];
            echo "</h2>";
            echo "</td>";
            
            echo "<td>";
            echo "<h2 >";
            echo $Element['employee_id'];
            echo "</h2>";
            echo "</td>";
            
            echo "<td>";
            echo "<h2 >";
            echo $Element['date'];
            echo "</h2>";
            echo "</td>";
            
            echo "</tr>";
        }
        
        echo "</table>";
    }
    
    
    
}
?>