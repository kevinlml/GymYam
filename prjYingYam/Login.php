<!DOCTYPE html>
<?php ob_start(); ?> 
<?php 
if(!class_exists('USER')){ include "classes/user.php"; }

if(!class_exists('Member')){ include "classes/member.php"; }

if(!class_exists('EMPLOYEE')){ include "classes/employee.php"; }
?>
 

<html lang="en">
<head>
   <title>GymYam</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
   .Box {
    width: 500px;
    border: 10px solid #CCCC00;
    padding: 25px;
    margin: 25px;
	margin-left:auto;
	margin-right:auto;
	}
	body {
    background-image: url("pic/bk2.jpg");
    
	}
   

    .auto-style1 {
        color: #CCCC00;
    }
    .auto-style2 {
        background-color: #000000;
    }
   

  </style>
  
  </head>
<body>

    <?php
 
				$login_error = '';
				$login_confirm = '';
                $user_id;
    
    

				if(! isset($_SESSION)){
					session_start();
				}
				 
				if(isset($_POST['submit']) && !empty($_POST['username']) && !empty($_POST['password']))
				{
							$username = $_POST['username'];
							$password = $_POST['password'];
							//Send a query to database to check the username and password
										
							
							$result = USER::Login($username, $password);
                    
							 if(!$result){
								
								$login_error = '<img src="images/No.png" width="25px" />';
								$login_error .= '<strong>   Error : Username and Password combination is incorrect !</strong><br/><br/><br/><br/>';
							}else{
								
								$login_confirm = "<strong>   Your Login is Successfull ! </strong><br/><br/><br/><br/>";
                                 
                                 switch($result){
                                     case 'Member' :
                                         
                                         $login_confirm .= "Student";
                                        
                                         $user_id= Member::Get_MemberID($username) ; 
                                         $_SESSION['Role_ID'] = $user_id;
                                    
                                        header("Location: Member.php");
                                         
                                    break;
                                     case 'Trainer' :
                                         
                                         $login_confirm .= "Trainer";
                                                  
                                         $user_id=  EMPLOYEE::Get_EmployeeID($username) ;
                                         $_SESSION['Role_ID'] = $user_id;
                                         
                                         header("Location: Trainer.php");
                                         
                                         break;
                                 }
                                     
                                   
							}
						
					}
				?>
<<nav class="navbar navbar-inverse navbar-fixed-top" style="background-color:#154360">
<div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php" style="color:white;">GymYam</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.php" style="background-color:#154360" style="color:white;">Home</a></li>
     
    </ul> 
	</div>
</nav>
    <br/><br/>
          <  <font color=#CC0000><?php echo $login_error; ?></font> 
            <font color=#006600><?php echo $login_confirm; ?></font> 
            <br/><br/>
</br>
</br>
<div class="Box" >
<form action="#" method="post">
  <div class="form-group">
      <span class="auto-style1">
    <label for="username"><span class="auto-style2">Username</span></label></span><label for="username" style="color:#154360">:</label>
    <input type="text" class="form-control" name="username">
  </div>
  <div class="form-group">
      <span class="auto-style1">
    <label for="password"><span class="auto-style2">Password</span></label></span><label for="password" style="color:#154360">:</label>
    <input type="password" class="form-control" name="password">
  </div>
  <div class="form-check">
    <label class="form-check-label" style="color: #CCCC00">
      <input class="form-check-input" type="checkbox" style="color:#154360"> Remember me
    </label>
	</br>
  </div>
  <button type="submit" class="btn btn-primary" style="background-color:#CCCC00" name="submit">Submit</button>
</form>
</div>
</body>
</html>