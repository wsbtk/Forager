<!DOCTYPE html>
<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script type="text/javascript">
<!--
function ajaxFunction(){
 var ajaxRequest;  // The variable that makes Ajax possible!
  
 try{
   // Opera 8.0+, Firefox, Safari
   ajaxRequest = new XMLHttpRequest();
 }catch (e){
   // Internet Explorer Browsers
   try{
      ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
   }catch (e) {
      try{
         ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
      }catch (e){
         // Something went wrong
         alert("Your browser broke!");
         return false;
      }
   }
 }
 // Create a function that will receive data 
 // sent from the server and will update
 // div section in the same page.
 ajaxRequest.onreadystatechange = function(){ 
   if(ajaxRequest.readyState == 4){
      var ajaxDisplay = document.getElementById('result');
      ajaxDisplay.innerHTML = ajaxRequest.responseText;
      var timer=setInterval(ajaxFunction,500)
   }
 }
 ajaxRequest.open("GET", "crawl_clean.php", true);
 ajaxRequest.send(null); 
}


 -->
</script>

</head>
<body>


<h3 id="updtCount">Start typing a name in the input field below:</h3>

<button type="button" onclick="ajaxFunction()">Start Crawl</button>
<p>Scanned: <span id="result"></span></p> 

</body>
</html>

