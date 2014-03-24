<?php 
echo $dd = 'HelloWorld';
session_start();

$host = "waggleAdmin.db.10586941.hostedresource.com";
$user = "waggleAdmin";
$db = "waggleAdmin";
$pass = "Waggle!@#1";
mysql_connect($host, $user, $pass) or die("Unable To Connect!");
mysql_select_db($db);
if(isset($_POST['email'])){        $email = isset($_POST["email"]) ? mysql_real_escape_string($_POST['email']) : exit("You must enter your email!");
    $fname = isset($_POST["fname"]) ? mysql_real_escape_string($_POST['fname']) : '';
    $lname = isset($_POST["lname"]) ? mysql_real_escape_string($_POST['lname']) : '';
    $password = strlen($_POST['password'])!=0 ? md5(mysql_real_escape_string(($_POST['password']))) : exit("You must enter a password");
    $sqs = isset($_POST['securityqestion']) ? mysql_real_escape_string($_POST['securityqestion']) : '';
    $sans = isset($_POST['securityanswer']) ? mysql_real_escape_string($_POST['securityanswer']) : '';
	    $domain = array('spsu.edu');
	    $check = "/^[a-z0-9._%+-]+@[a-z0-9.-]*(".implode('|',$domain).")$/i";
	    $checkuser = mysql_query("SELECT*FROM wagglers WHERE email = '".$email."'");
		//$checkuser = mysql_query("SELECT 'email','fname','lname' FROM wagglers WHERE email = '".$email."'");
	    if(mysql_num_rows($checkuser) == 1)	{		header("location:sign-up.php?notify = you are registered already");
	}    if(!preg_match($check, $email))	{		header("location:sign-up.php?notify = you need a spsu email");
	}    if(mysql_num_rows($checkuser) !=1 && preg_match($check, $email))        {		
			mysql_query('INSERT into wagglers(email,fname,lname,password,securityq,securitya)VALUES("'.$email.'","'.$fname.'","'.$lname.'","'.$password.'","'.$sqs.'","'.$sans.'")');
		echo ("Welcome to Waggle<br /> " .$fname ." " .$lname);
	}}?>