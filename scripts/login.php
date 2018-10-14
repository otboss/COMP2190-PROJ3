<?
    //INCLUDES THE CONFIGURATION FILE
    require_once "config.php";
    date_default_timezone_set("Jamaica");
    session_start();
    define('timeoutPeriod', 5);
    
    if(!isset($_SESSION['attempts'])){
        $_SESSION['attempts'] = 0;
    }
    if(isset($_SESSION['timeout'])){
        if(time() - $_SESSION['timeout'] == timeoutPeriod*60){
            $_SESSION['attempts'] = 0;
            unset($_SESSION['timeout']);
        }    
    }
    
    //FUNCTION TO PREVENT SQL INJECTION
    function cleanString($string){
        return strip_tags(addslashes($string));
    }
    
    //IF POST REQUEST HAS BEEN MADE
    if($_POST){
        $username = cleanString($_POST['username']);
        $password = cleanString($_POST['password']);
        
        $sql = "SELECT * FROM `Users` WHERE username='$username'";
        $query = mysqli_query($link, $sql);
        
        //VALIDATES THE CURRENT LOGIN, CREATES SALTED PASSWORD USING 
        //SALT PROVIDED IN THE DATABASE AND THE USER PASSWORD ENTERED
        //ALSO MATCHES THE USERNAME ENTERED WITH ONE IN THE DATABASE
        function validate($username, $password, $query, $link){
            while($row = mysqli_fetch_array($query)){
                if(md5($password.$row['salt']) == $row['password_digest']){
                    $date = date("Y/m/d H:i ").date_default_timezone_get();
                    $sql = "UPDATE `Users` SET last_login='$date' WHERE username='$username'";
                    mysqli_query($link, $sql);
                    $_SESSION['attempts'] = 0;
                    return true;
                }
                $failed_attempts = $row['failed_attempts'] + 1;
                $sql = "UPDATE `Users` SET failed_attempts='$failed_attempts' WHERE username='$username'";
                //INCREMENTS FAILED LOGINS
                mysqli_query($link, $sql);                    
            }
            return false;
        }      
                      
        if(validate($username, $password, $query, $link) == false){
            $_SESSION['attempts']++;
            if($_SESSION['attempts'] >= 5){
                if(!isset($_SESSION['timeout'])){
                    $_SESSION['timeout'] = time();
                }
                die("Too many failed login attempts. ".timeoutPeriod." minute(s) of cooldown.");
            }            
            //DISPLAY LOGIN ERROR MESSAGE
            ?>
                <script type="text/javascript">
                    window.onload = function(){
                        var errmsg = window.document.getElementById('errorDiv');
                        errmsg.innerHTML = "An error occured with the last login";
                        errmsg.style.color = "red";
                        errmsg.style.display = '';
                    }                    
                </script>
            <?
        }
        else{
            //DISPLAY SUCCESS MESSAGE
            ?>
                <script type="text/javascript">
                    window.onload = function(){
                        var errmsg = window.document.getElementById('errorDiv');
                        errmsg.innerHTML = "Login successful";
                        errmsg.style.color = "green";
                        errmsg.style.display = '';    
                    }                    
                </script>
            <?  
        }

    }
?>


<html>
    <head>
        <meta charset="UTF-8"> 
        <title>User login</title>
        <style type="text/css">
            body{
                margin: 0;
            }
            span{
                font-family: arial;
            }
            input{
                border: 0px solid;
                border-bottom: 1px solid #00B0AA;
                padding: 3px 5px;
                font-size: 18px;
                color: #00A6A0;
                background-color: transparent;
            }
            ::-webkit-input-placeholder {
               text-align: center;
               color: #8AC0BE;
            }
            :-moz-placeholder { /* Firefox 18- */
               text-align: center;  
               color: #8AC0BE;
            }

            ::-moz-placeholder {  /* Firefox 19+ */
               text-align: center;  
               color: #8AC0BE;
            }

            :-ms-input-placeholder {  
               text-align: center; 
               color: #8AC0BE;
            }         
            button{
                cursor: pointer;
            }                
        </style>
    </head>
    
    <body>
        <header style="width: 100%; height: 60px; float: left; heigth: 100px; background-color: ; border-bottom: 1px solid #39B5B1;">
            <span style="font-size: 28px; color: #39B5B1; float: left; padding: 15px 0 0 15px;">PesticideCtrl</span>
        </header>
        <div style="width: 100%; float: left;">
            <br>
            <span style="margin: 0 0 0 20px; font-size: 24px; color: #39B5B1;">User Login</span>
            <br>
            <span id="errorDiv" style="float: left; margin: 0 0 0 20px; color: red; display: none;">An error occured with the last login</span>
        </div>
        <div style="float: left ; position: relative; width: 300px; height: 165px; margin: 30px 0 0 50px; border: 1px solid #39B5B1; text-align: center;">
            <form method="post">
                <div style="display: inline-block;">
                    <table>
                        <tr>
                            <td style="padding: 18px 0;">
                                <input id="username" placeholder="username" name = "username" style="width: 100%;"></input>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input id="password" name="password" placeholder="password" type="password" style="width: 100%;"></input>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input id="hidden_field" placeholder="?" type="hidden" style="width: 100%;"></input>
                            </td>
                        </tr>
                    </table>
                </div>
                <div>
                    <button style="color: white; background-color: #39B5B1; width: 100%; position: absolute; bottom: 0; left: 0; border: 0; padding: 10px 0;">submit</button>
                </div>
            </form>
        </div>
    </body>
</html>
