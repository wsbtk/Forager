<!--<html>
	<body>

		<form action = 'WaggleUpload.php' method = 'post' enctype='multipart/form-data'>
			<table>	
				<td>
					<button type="button" onclick="location.href='http://localhost/Waggle/Files/WaggleFIleManage/Display_1.php'">Back to folder</button>
				</td>
				<tr>
					<tr>
					<td><input type = "file" name="myfiles[]" multiple="multiple" min="1" max="99"></td>
					</tr>
					<tr>
						<td><input type="submit" value="Submit"></td>
					</tr>
				</tr>
			</table>
		</form>
	</body>
</html>-->
	
<?php
//require("header_sign-in.php");
session_start();
if (isset($_FILES['myfiles'])) {
	
	$db_host="localhost";
	$db_user="root";
	$db_name="waggleAdmin";
	$db_pass="";
	
	$con = mysqli_connect("$db_host", "$db_user", "$db_pass", "$db_name"); 
	foreach ($_FILES['myfiles']['tmp_name']as$key=>$tmp_name) {
	
	//file name
	$name = $_FILES['myfiles']['name'][$key];
	//$folderName = $_SESSION['']['folderName'];
	$usern = $_SESSION['user']['folderName'];
	//$dirf = '/Applications/XAMPP/xamppfiles/htdocs/Waggle/Files/WaggleFIleManage/Self/'.$usern.'/';
	$dirf = '/Applications/XAMPP/xamppfiles/htdocs/Waggle/Files/'.$usern.'/';
	echo "<br/>";
	//Time and Date	
	$date = new DateTime(null, new DateTimeZone('EST'));
	$date1 =  date_format($date,'Y-m-d H:i:s');
	//size
	$size = round(filesize($tmp_name)/1024, 2);

	echo  " $name <br/> $size KB <br/>$date1 <br/>";
	$user = $_SESSION['user']['email'];
	
	if ( $size < 10240 ) {
		
		if (file_exists($dirf.$name)){
			move_uploaded_file($tmp_name, $dirf.time().$name);
			$name1 =time().$name;
			$_SESSION['user']['filename'] = $name1;
			echo "Upload Completed.<br/> File Existed. Sorted by time.<br/>";
			mysqli_query($con, "INSERT INTO files (file_name, file_size, file_date, email) VALUES( '$name1','$size','$date1', '$user')");

		}
		else {
			$_SESSION['user']['filename'] = $name;
			move_uploaded_file($tmp_name, $dirf.$name);
			echo "Upload Completed.<br/>";
			mysqli_query($con, "INSERT INTO files (file_name, file_size, file_date) VALUES( '$name','$size','$date1','$user')");
		}
	echo "<a href = 'FileManage.php'>Back to Folder</a>";
	}

	else {
		echo "Failed to upload. You have exceed the Maximum Size 10240 KB.";	
	}

	}
}
else {
	echo "Please select your file (Max Size:10240 KB) ";
	
}


?>