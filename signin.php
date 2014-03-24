<?php
    session_start();
?>
    <!-- Sign In Option 1 -->
    <!--<div id="float-left" >
        <img alt="spsulogo" src="img/spsu_logo_centered.png" />
        </div>-->
    <div id="sign_in">
        <br /><br /><br />
        <h4>Welcome to Forager!</h4>
        <p>!! Lets do some Web Crawling !!</p>
        <form method ="post"> <!-- action ="main_landing.php" -->
            <input type="text" name ="UserID" placeholder="User ID" ><br /><br />
            <input type="password" name ="Password" placeholder="Password" ><br /><br />
            <label class="checkbox">
                <input type="checkbox"> <span>Remember me</span>
            </label>&emsp;
            <input type="submit" value="Sign In"><br /><br />
            <a href="index.php?page=reset">Forgot password?</a>
        </form>
    </div>