<?php
       //Variables for connecting to your database.
       //These variable values come from your hosting account.
       $hostname = "ForagerAdmin.db.10586941.hostedresource.com";
       $username = "ForagerAdmin";
       $dbname = "ForagerAdmin";
       //These variable values need to be changed by you before deploying
       $password = "Te@mQu4tro";
       
       $usertable = "your_tablename";
       $yourfield = "your_field";

       
//Connecting to your database
       mysql_connect($hostname, $username, $password) OR DIE ("Unable to 
       connect to database! Please try again later.");
       mysql_select_db($dbname);


       //Fetching from your database table.
       $query = "SELECT * FROM $usertable";
       $result = mysql_query($query);

       
       
echo '
<html>
<head></head>
<body>
<div id="blank"> 
        <h1>Let\'s get this project done.</h1>
        <p>This is just a placeholder.</p>
        <p><a href="crawl.php">Crawl a site</a></p>
</div>

</body>
</html>';