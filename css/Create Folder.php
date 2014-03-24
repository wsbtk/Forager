
<?php
session_start();

$db_host="localhost";
$db_user="root";
$db_name="documents";
$db_pass="";

$con = mysqli_connect("$db_host", "$db_user", "", "$db_name"); 
$query = mysql_query("SELECT * FROM documents WHERE email ='".$email."';");
$row = mysql_fetch_array($query);
$foldername =$row['name'];

$dirPath = "/Applications/XAMPP/xamppfiles/htdocs/Waggle/Files/WaggleFIleManage/Self/$foldername";	
if (!file_exists("$foldername")) {
	mkdir($dirPath, 0777);
	echo "Folder Created";	
} 
else {
	include 'WaggleUpload.php';
}
	
?>