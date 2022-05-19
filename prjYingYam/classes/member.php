<?php
if(!class_exists('PERSON')){ include "person.php"; }


class Member extends PERSON{
    
    
    public function Create(){
        
        //Create user account first
        $this->user->Create();
        
        
        //creating entry on student table
        $query = "INSERT INTO member ( name, email, phone,  username) ";
        $query .= "VALUES(?,?,?,?)";
        PERSON::Init_Database();
        
        try{
            $sql = PERSON::$database->Connection->prepare($query);
            $sql->bindParam(1, $this->name);
            $sql->bindParam(2, $this->email);
            $sql->bindParam(3, $this->phone);
            $sql->bindParam(4, $this->user->GetUsername());
            
            
            $sql->execute();
            
            $last_id = self::$database->Connection->LastInsertId();
            
            return $last_id;
            
        }catch(PDOException $e){
            echo "Query INSERT Failed ".$e->getMessage();
        }
    }
    
    public static function AddCourse($member_id, $course_id, $status){
        
        $query = "INSERT INTO member_course ( member_id, course_id, status) ";
        $query .= "VALUES(?,?,?)";
        PERSON::Init_Database();
        
        try{
            $sql = PERSON::$database->Connection->prepare($query);
            $sql->bindParam(1, $member_id);
            $sql->bindParam(2, $course_id);
            $sql->bindParam(3, $status);
            
            
            $sql->execute();
            
            return true;
            
        }catch(PDOException $e){
            echo "Query INSERT Failed ".$e->getMessage();
        }
    }
    
    
    public function Delete($member_id){
        
        $query  = "DELETE FROM member  ";
        $query .= "WHERE member_id = $member_id";
        PERSON::Init_Database();
        try{
            PERSON::$database->Connection->exec($query);
            USER::Delete(self::Get_Username($member_id));
            return true;
        }catch(PDOException $e){
            echo "Query UPDATE Failed ".$e->getMessage();
            return false;
        }
    }
    
    public static function Get_Username($member_id){
        self::init_database();
        $connection = self::$database->Connection;
        try{
            $query = "SELECT username FROM member WHERE member_id = $member_id ";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $userObj = $stmt->fetch(PDO::FETCH_OBJ);
            
            return $userObj->username;
            
        }catch(PDOException $e){
            echo "Query Failed ".$e->getMessage();
        }
    }
    
    public static function Get_MemberID($username){
        self::init_database();
        $connection = self::$database->Connection;
        try{
            $query = "SELECT `user`.username, member.member_id
FROM     `user` INNER JOIN
                  member ON `user`.username = member.username
WHERE  `user`.username = $username ";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $userObj = $stmt->fetch(PDO::FETCH_OBJ);
            
            return $userObj->member_id;
            
        }catch(PDOException $e){
            echo "Query Failed ".$e->getMessage();
        }
    }
    
    public function Update($member_id){
        
        
        //Create user account first
        USER::Update_Password($this->user->GetUsername(), $this->user->GetPassword());
        
        
        //creating entry on student table
        $query = "UPDATE member ";
        $query .= "SET name = ? , email = ? , phone = ? ";
        $query .= "WHERE member_id = $member_id ";
        
        
        PERSON::Init_Database();
        
        try{
            $sql = PERSON::$database->Connection->prepare($query);
            $sql->bindParam(1, $this->name);
            $sql->bindParam(2, $this->email);
            $sql->bindParam(3, $this->phone);
            
            $sql->execute();
            
            
        }catch(PDOException $e){
            echo "Query INSERT Failed ".$e->getMessage();
        }
        
    }
    
    public static function ListAll(){
        
        PERSON::Init_Database();
        
        $connection = self::$database->Connection;
        $query  = "SELECT * FROM member";
        
        try{
            $stmt = $connection->prepare($query);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            
        }catch(PDOException $e)
        {
            echo "Query Read Members Failed: ".$e->getMessage();
        }
    }
    
    
    public static function Display($array){
        
        
        echo "<table  style='border-spacing: 4px; border-collapse: separate; color:white' >";
        
        echo "<th>Member ID</th>";
        echo "<th>Name</th>";
        echo "<th>Email</th>";
        echo "<th>Phone</th>";
        echo "<th>UserName</th>";
        
        foreach($array as $Index => $Element){
            echo "<tr>";
            echo "<td>";
            echo $Element['member_id'];
            echo "</td>";
            
            echo "<td>";
            echo "<h2>";
            echo $Element['name'];
            echo "</h2>";
            echo "</td>";
            
            echo "<td>";
            echo "<h2>";
            echo $Element['email'];
            echo "</h2>";
            echo "</td>";
            
            
            echo "<td>";
            echo "<h2>";
            echo $Element['phone'];
            echo "</h2>";
            echo "</td>";
            
            echo "<td>";
            echo "<h2>";
            echo $Element['username'];
            echo "</h2>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
    
    
    
    public static function ListMemberCourses($member_id){
        
        PERSON::Init_Database();
        
        $connection = self::$database->Connection;
       /* $query  = "SELECT c.course_id, c.name, c.start_date, c.end_date, c.schedule, c.price, c.employee_id, sc.status ";
        $query .= "FROM member_course sc, course c";
        $query .= "WHERE sc.member_id = $member_id AND c.course_id = sc.course_id";
        */
        $query ="SELECT member_course.member_id, member_course.course_id, course.course_id AS Expr1, course.name, course.start_date, course.end_date, course.schedule, course.price, member_course.status
FROM     member_course INNER JOIN
                  course ON member_course.course_id = course.course_id
WHERE  member_course.member_id = 1";
        
        try{
            $stmt = $connection->prepare($query);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            
        }catch(PDOException $e)
        {
            echo "Query Read member Failed: ".$e->getMessage();
        }
    }
    
    
    
    public static function DisplayMemberCourses($array){
        
        
        echo "<table  border=0 style='border-spacing: 4px; border-collapse:separate; color:white' >";
        
        echo "<th>Course ID</th>";
        echo "<th>Name</th>";
        echo "<th>Start Date</th>";
        echo "<th>End Date</th>";
        echo "<th>Schedule</th>";
        echo "<th>Price</th>";
        echo "<th>Status</th>";
        
        
        foreach($array as $Index => $Element){
            echo "<tr>";
            echo "<td>";
            echo $Element['Expr1'];
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
            
            echo "<td>";
            echo "<h2 >";
            echo $Element['price'];
            echo "</h2>";
            echo "</td>";
            
            echo "<td>";
            echo "<h2 >";
            echo $Element['status'];
            echo "</h2>";
            echo "</td>";
            
            echo "</tr>";
        }
        
        echo "</table>";
    }
    
    
    
    
    
    public static function Get_Student_Name($student_id) {
        //TODO ...
        $studentName = 'Student';
        
        self::Init_Database();
        
        $connection = self::$database->Connection;
        $query = "SELECT * FROM student WHERE member_id = $student_id ;";
        
        try{
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $userObj = $stmt->fetch(PDO::FETCH_OBJ);
            $studentName = $userObj->name;
            
        }catch(PDOException $e){
            echo "Query Failed ".$e->getMessage();
        }
        
        return $studentName;
    }
    
}
?>