<?php
session_start();
if(isset($_GET['logout'])){    
	
	//Simple exit message 
    $logout_message = "<div class='msgln'><span class='left-info'>User <b class='user-name-left'>". $_SESSION['name'] ."</b> has left the chat session.</span><br></div>";
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
	
	session_destroy();
	header("Location: index.php"); //Redirect the user 
}
if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    }
    else{
        echo '<span class="error">Please type in a name</span>';
    }
}
function loginForm(){
    echo 
    '<div id="loginform"> 
<p><img src="https://i.pinimg.com/564x/1f/77/b4/1f77b48874ca650337472a3215bc30bc.jpg" alt=""
width:50px; height:50px;">
<p>Bạn muốn chúng mình gọi bạn là gì nè?</p> 
<form action="index.php" method="post"> 
<label for="name">Tên &mdash;</label> 
<input type="text" name="name" id="name" /> 
<input type="submit" name="enter" id="enter" value="Enter" />
</form> 
<img src="https://i.pinimg.com/564x/1e/78/10/1e7810bf2a12cc94f7f60015cee4228b.jpg" height="30%" width="30%" alt ="https://i.pinimg.com/564x/22/a3/fe/22a3fe28e0c49a5b5b2a56d53de7e7c6.jpg">
</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Góc tối nhỏ</title>
        <meta name="description" content="Góc tối nhỏ" />
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
    <?php
    if(!isset($_SESSION['name'])){
        loginForm();
    }
    else {
    ?>
        <div id="wrapper">
            <div id="menu">
                <p class="welcome">Chào mừng đến với Góc tối nhỏ, <b><?php echo $_SESSION['name']; ?></b></p>
                <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
            </div>
            <div id="chatbox">
            <?php
            if(file_exists("log.html") && filesize("log.html") > 0){
                $contents = file_get_contents("log.html");          
                echo $contents;
            }
            ?>
            </div>
            <form name="message" action="">
                <input name="usermsg" type="text" id="usermsg" />
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
            </form>
        </div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            // jQuery Document 
            $(document).ready(function () {
                $("#submitmsg").click(function () {
                    var clientmsg = $("#usermsg").val();
                    $.post("post.php", { text: clientmsg });
                    $("#usermsg").val("");
                    return false;
                });
                function loadLog() {
                    var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request 
                    $.ajax({
                        url: "log.html",
                        cache: false,
                        success: function (html) {
                            $("#chatbox").html(html); //Insert chat log into the #chatbox div 
                            //Auto-scroll 
                            var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request 
                            if(newscrollHeight > oldscrollHeight){
                                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div 
                            }	
                        }
                    });
                }
                setInterval (loadLog, 500);
                $("#exit").click(function () {
                    var exit = confirm("Are you sure you want to end the session?");
                    if (exit == true) {
                    window.location = "index.php?logout=true";
                    }
                });
            });
        </script>
    </body>
</html>
<?php
}
?>