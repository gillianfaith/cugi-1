<?php include('connect.php') ?>
<?php
// Starting the session, necessary
// for using session variables
//session_start();

// Declaring and hoisting the variables
$first_name = "";
$last_name = "";
$username = "";
$errors = array();
$_SESSION['success'] = "";

// DBMS connection code -> hostname,
// username, password, database name
//$db = mysqli_connect('localhost', 'root', '', 'moko');

// Registration code
if (isset($_POST['submit'])) {

	$first_name = mysqli_real_escape_string($db, $_POST['first_name']);
	$last_name = mysqli_real_escape_string($db, $_POST['last_name']);
	$username = mysqli_real_escape_string($db, $_POST['email']);
	// $role_id = mysqli_real_escape_string($db, $_POST['role_id']);
	$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
	$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

	// Ensuring that the user has not left any input field blank
	// error messages will be displayed for every blank input
	if (empty($first_name)) {
		array_push($errors, "First Name is required");
	}
	if (empty($last_name)) {
		array_push($errors, "Last Name is required");
	}
	if (empty($username)) {
		array_push($errors, "Email is required");
	}
	if (empty($password_1)) {
		array_push($errors, "Password is required");
	}

	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
		// Checking if the passwords match
	}
	$select = mysqli_query($db, "SELECT email FROM users WHERE email = '" . $_POST['email'] . "'") or exit(mysqli_error($db));
	if (mysqli_num_rows($select)) {
		header('location: login.php');
		exit('This email is already being used');
	}
	// If the form is error free, then register the user
	if (count($errors) == 0) {

		// Password encryption to increase data security
		$password = md5($password_1);

		// Inserting data into table
		$query = "INSERT INTO users (email,password,first_name,last_name)
				VALUES('$username', '$password','$first_name','$last_name')";

		mysqli_query($db, $query);

		// Storing username of the logged in user,
		// in the session variable
		$_SESSION['email'] = $username;

		// Welcome message
		$_SESSION['success'] = "You have logged in";

		// Page on which the user will be
		// redirected after logging in
		header('location: login.php');
	}
}
//end of registration

// User login
if (isset($_POST['log_in'])) {

	// Data sanitization to prevent SQL injection
	$username = mysqli_real_escape_string($db, $_POST['email']);
	$password = mysqli_real_escape_string($db, $_POST['password']);

	// Error message if the input field is left blank
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}


	// Checking for the errors
	if (count($errors) == 0) {

		// Password matching
		$password = md5($password);

		$query = "SELECT * FROM users WHERE email =
				'$username' AND password='$password'";
		$results = mysqli_query($db, $query);

		// $results = 1 means that one user with the
		// entered username exists
		if (mysqli_num_rows($results) > 0) {
			$row = mysqli_fetch_assoc($results);
			$cur_username = $row['email'];
			$cur_password = $row['password'];
			$cur_status = $row['status'];
			$cur_first_name = $row['first_name'];
			$cur_organization_id = $row['organization_id'];
			$cur_role_id = $row['role_id'];
			$cur_status = $row['status'];
			$cur_usertype = $row['user_type'];

			if ($cur_status == "1" && $cur_username == $username  && $cur_password == $password) {
				// Storing username in session variable
				$_SESSION['email'] = $username;

				// Welcome message
				$_SESSION['success'] = 'Welcome ' . $cur_first_name . '.';

				// Page on which the user is sent
				// to after logging in
                header('location: index.php');

			} else {
				// If the username and password doesn't match
				array_push($errors, "Username or password incorrect");
			}
		} else {
			// If the username and password doesn't match
			array_push($errors, "User does not exists");
		}
	}
    //end of login

}

	//forgot password
	if(isset($_POST['submit_email'])){ 
		$email=$_REQUEST['email'];
		$query = "SELECT * from  users where (email='$email')"; 
		$res = mysqli_query($db,$query);
		$count=mysqli_num_rows($res);

		// $results = 1 means that one user with the
		// entered email exists
		if($count==1)
		{
		$findresult = mysqli_query($db, "SELECT * FROM users WHERE (email='$email')");
		if($res = mysqli_fetch_array($findresult))
		{
		$oldftemail = $res['email'];  
		}
		$token = bin2hex(random_bytes(50));
		 $inresult = mysqli_query($db,"insert into pass_reset values('','$oldftemail','$token')"); 
		 if ($inresult)  
		 { 
		$FromName="Cugi";
		$FromEmail="no_reply@cugi.info.com";
		$ReplyTo="mail@cugi.info.com";
		$credits="All rights are reserved | Cugi "; 
		$headers  = "MIME-Version: 1.0\n";
			 $headers .= "Content-type: text/html; charset=iso-8859-1\n";
			 $headers .= "From: ".$FromName." <".$FromEmail.">\n";
			  $headers .= "Reply-To: ".$ReplyTo."\n";
			  $headers .= "X-Sender: <".$FromEmail.">\n";
			   $headers .= "X-Mailer: PHP\n"; 
			   $headers .= "X-Priority: 1\n"; 
			   $headers .= "Return-Path: <".$FromEmail.">\n"; 
				 $subject="You have received password reset email"; 
			 $msg="Your password reset link <br> http://localhost:8081/cugi/password-reset.php?token=".$token." <br> Reset your password with this link .Click or open in new tab<br><br> <br> <br> <center>".$credits."</center>"; 
		   if(@mail($oldftemail, $subject, $msg, $headers,'-f'.$FromEmail) ){
		header("location:forgot-password.php?sent=1"); 
		$hide='1';
				  
			} else {
				
			header("location:forgot-password.php?servererr=1"); 
		} 
			  } 
			  else 
			  { 
				  header("location:forgot-password.php?something_wrong=1"); 
			  }     
		}
		else  
		{
		header("location:forgot-password.php?err=".$email); 
		}
	}
	//end of forgot password
	
	//reset password
	if(isset($_GET['token']))
		{
	$token= $_GET['token'];
	}
	//form for submit 
	if(isset($_POST['submit_reset'])){
		$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
	
	extract($_POST);
	if($password_1 ==''){
		$error[] = 'Please enter the password.';
	}
	if($password_2 ==''){
		$error[] = 'Please confirm the password.';
	}
	if($password_1 != $password_2){
		$error[] = 'Passwords do not match.';
	}
	if(strlen($password_1)<5){ // min 
	$error[] = 'The password is 6 characters long.';
	}
	if(strlen($password_1)>50){ // Max 
		$error[] = 'Password: Max length 50 Characters Not allowed';
	}
	$fetchresultok = mysqli_query($db, "SELECT email FROM pass_reset WHERE token='$token'");
	if($res = mysqli_fetch_array($fetchresultok))
	{
	$email= $res['email']; 
	}
	if(isset($email) != '' ) {
	$emailtok=$email;
	}
	else 
	  { 
	 $error[] = 'Link has been expired or something missing ';
	  $hide=1;
	  }
	if(!isset($error)){
	$options = array("cost"=>4);
	// $password = password_hash($password_1,PASSWORD_BCRYPT,$options);
	$password = md5($password_1);
	$resultresetpass= mysqli_query($db, "UPDATE users SET password='$password' WHERE email='$emailtok'"); 
	if($resultresetpass) 
	{ 
	$success="<div class='successmsg'><span style='font-size:100px;'>&#9989;</span> <br> Your password has been updated successfully.. <br> <a href='login.php' style='color:#fff;'>Login here... </a> </div>";

	$resultdel = mysqli_query($db, "DELETE FROM pass_reset WHERE token='$token'");
	$hide=1;
	}
	} 
	}
