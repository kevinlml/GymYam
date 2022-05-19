<?php


include "classes/member.php";
?>
<!DOCTYPE html>

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
	 /*<!-- Pic_resurse: https://www.google.ca/search?q=background+dance&tbm=isch&tbs=rimg:Cf8U45YgABJ7IjjjFZSbX5RrQXvM_1nVOo5UGxsfaCkqxOhuBw_1UG3BtxiGOng5KaqUkXxvbilh7KMT6eGUt3JSPYNyoSCeMVlJtflGtBER8HJa0Gdvp3KhIJe8z-dU6jlQYRj_1vr3ta7XzIqEgnGx9oKSrE6GxF5m29lxuIpzCoSCYHD9QbcG3GIEUGeLnj8n41fKhIJY6eDkpqpSRcRLp3XpX1hjD8qEgnG9uKWHsoxPhH_1HGj962uyvioSCZ4ZS3clI9g3EWKiMRyvKN-F&tbo=u&sa=X&ved=2ahUKEwiny-LX94LaAhXHJt8KHXHiCBcQ9C96BAgAEBs&biw=1280&bih=566&dpr=1.5#imgrc=5jmhbWArRINi_M:
	 -->*/
   
    .auto-style1 {
        background-color: #000000;
    }
   
  </style>
   
  </head>
<body>

 
<<?php
		$password_conf_error = "";
		$email_error = "";
		$register_confirm = "";
		
	if(isset($_POST['Submit']) && !empty($_POST['Username'])&& !empty($_POST['Password']))
    {
        
        $username = $_POST['Username'];
		
        
        //Send a query to database to check if the user already exists 
		if(USER::Exists($username)){
			
            $email_error .= '  Error : Username already exist !<br/><br/>';
        }
		
		//Send a query to database to create new user
		if(! USER::Exists($username) ){
		

            $name = $_POST['Name'];
            $email = $_POST['Email'];
            $phone = $_POST['Phone'];
            $password = $_POST['Password'];
            $user_type = "Member";
            
            //Send a query to database to insert the data of the new user 
			
			
            $newMember = new Member($name , $email, $phone, new USER($username, $password, $user_type));
			
            $newMember_id = $newMember->Create();
			
            $register_confirm = "   Your account is successfully created ! <br>";
			$register_confirm .= "   Please, login on Sign-In page.<br>";
			$register_confirm .= "   Your Member ID is $newMember_id <br><br><br><br>";
			
        }
		
    }	
			
			?>
			
<nav class="navbar navbar-inverse navbar-fixed-top" style="background-color:#CCCC00">
<div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php" style="color:#000000;">GymYam</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.php" style="background-color:#CCCC00; color: #000000;" style="color:white;">Home</a></li>
     
    </ul>
   
  </div>
</nav>
<div>
</br>
</br>

<br/><br/>
			<font color=#CC0000><?php echo $password_conf_error; ?></font> 			
			<font color=#CC0000><?php echo $email_error; ?></font> 
            <font color=#006600><?php echo $register_confirm; ?></font> 
            <br/><br/>	 

<div class="Box">
<form action="#" method="post">
 <div class="form-group">
    <label for="name" style="color:#CCCC00"><span class="auto-style1">Name</span>:</label>
    <input type="name" class="form-control" name="Name">
  </div>
  <div class="form-group">
     <label for="phone" style="color:#CCCC00" ><span class="auto-style1">Phone</span>:</label>
    <input type="text" class="form-control" name="Phone">
  </div>
  <div class="form-group">
     <label for="email" style="color:#CCCC00"><span class="auto-style1">Email address:</span></label>
    <input type="email" class="form-control" name="Email">
  </div>
  <div class="form-group">
    <label for="username" style="color:#CCCC00"><span class="auto-style1">Username</span>:</label>
    <input type="text" class="form-control" name="Username">
  </div>
  <div class="form-group">
    <label for="pwd" style="color:#CCCC00"><span class="auto-style1">Password:</span></label>
    <input type="password" class="form-control" name="Password">
  </div>
  <button type="submit" class="btn btn-primary" style="background-color:#CCCC00; color: #000000;" name="Submit">Submit</button>
</form>
</div>
</div>
</body>
</html>