<!doctype html>
<html>
<head>
    <title>FileManage</title>
</head>
	<body>
      	<p>Feel free to upload your files</p>
			<form enctype="multipart/form-data" action="WaggleUpload.php" method="POST">
				Choose files to upload: <input type = "file" name="myfiles[]" multiple="multiple" min="1" max="99"><br />
				<input type="submit" value="Upload" />
			</form>

        <?php
        
        session_start(); 
		$usern = $_SESSION['user']['folderName'];
		//$dir = 'Self/'.$usern.'/';
		$dir = 'Files/'.$usern.'/';

		echo "<br/>";
		echo "Welcome $usern: Your files have been list below. Have Fun in Waggle. ";
		echo "<br/>";
		echo "<br/>";
		echo "Note: Click Delete for delete a file & Click Rename for rename a file:";
		echo "<br/>";
			
		//function rename1(){
		//echo "<button type='button' onclick="."location.href='http://localhost/Waggle/Files/WaggleFIleManage/Rename.php'".">Rename_"." </button>";
		//}
		foreach (new DirectoryIterator($dir) as $file){
			//if (($file != '.') && ($file != '..')) {
				//$_SESSION['user']['filename1'] = $file;
				//$_SESSION['rename'][$id] = $file;
				
				//while ($file = readdir($handle)) {
			//if ($file != '.' && $file != '..') {
				//echo '<a href = "'.$dir.'/'.$file.'">'.$file.'</a><br>';
				//include 'Delete.php';	
				//include "Delete.php";
			if ($file->isDot()) continue;
				//$_SESSION['user']['filename1'] = $file;
				$name = $file->getFileName();
				//$temp = $dir.$name;
				global $Complete_Path;
				$Complete_Path = $dir.$name;
						//	echo "<button type='button' onclick="."location.href='http://localhost/Waggle/Files/WaggleFIleManage/Rename.php'".">Rename_".$file." </button> &emsp;";
				//echo '<a href ="'.$dir.$file.'">'.$id++.".".$file.'<br>';
				echo "<a href='".$dir."/".$name."'>".$name."</a>";
				echo "\t".'<a href = "Delete.php?name='.$Complete_Path.'"><img src ="Delete.png"></a>';
				echo "\t".'<a href = "FileManage_Rename.php?name='.$Complete_Path.'"><img src ="Rename.png"</a><br/>';
				//echo "<button type='button' onclick="."location.href='http://localhost/Waggle/Files/WaggleFIleManage/Rename.php'".">Rename_".$file." </button> &emsp;";
				//echo '<a href ="'.$dir.$file.'">'.$id++.".".$file.'<br>';
		}		
		?>	
	</body>
</html>