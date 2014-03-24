<?php 
//echo $dd = 'HelloWorld';
session_start();
$host = "ForagerAdmin.db.10586941.hostedresource.com";
$user = "ForagerAdmin";
$db = "ForagerAdmin";
$pass = "Te@mQu4tro";


mysql_connect($host, $user, $pass) OR DIE("Unable To Connect!");
mysql_select_db($db);

if(isset($_POST['UserID']))
{
    $user_id = mysql_escape_string($_POST['UserID']);
    $password = mysql_escape_string($_POST['Password']);
    $password1 = md5($password);
    
    
    $query = mysql_query("SELECT * FROM Forager_User WHERE UserID ='".$user_id."';");
    $row = mysql_fetch_array($query);

    if($password1 == $row['Password'])
    {    
        echo ("Welcome Admin");
        //echo ("foo");
        //Set session info
        //$_SESSION['user']=$row;

        print_r($_SESSION);
        exit();
    }
    else{
        header("Location: index.php?page=sign-up&passemail=".$user_id);
    }  
}
?>
