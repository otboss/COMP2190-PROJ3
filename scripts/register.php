<?  
    //INCLUDES THE CONFIGURATION FILE
    require_once "config.php";    
    date_default_timezone_set("Jamaica");
    
    //FUNCTION TO PREVENT SQL INJECTION
    function cleanString($string){
        return strip_tags(addslashes($string));
    }
    
    if(isset($_POST)){
        
        function validateEmail($email){
            if($email[0] == "@"){
                //IF THE EMAIL ENTERED DOES NOT START WITH @
                return false;
            }
            
            for($x = 0; $x < strlen($email); $x++){
                //EMAIL VALIDATION
                if($email[$x] == "@"){
                    if($email[$x+1]){
                        if($email[$x+1] != "."){
                            for($y = $x+1; $y < strlen($email); $y++){
                                if($email[$y] == "."){
                                    if($email[$y+1]){
                                        for($z = $y+1; $z < strlen($email); $z++){
                                            if(ctype_alpha($email[$z]) == false){
                                                //IF DOMAIN EXTENSION DOES NOT CONTAIN ONLY ALPHABETICAL LETTERS
                                                return false;
                                            }
                                            if($z == strlen($email) - 1){
                                                return true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return false;
        }
        
        function validateName($name){
            //IF NAME CONTAINS ONLY ALPHABETICAL LETTERS AND / OR HYPHENS
            if(strlen($name) != 0){
                for($x = 0; $x < strlen($name); $x++){
                    if (ctype_alpha($name[$x]) == false) {
                        if($name[$x] != " " && $name[$x] != "-"){
                            return false;
                        }
                    }  
                    if($x == strlen($name) - 1){
                        return true;
                    }
                }                
            }
            return false;
        }
        
        //FUNCTION TO DETERMINE IF PASSWORDS MATCH
        function validatePasswords($password, $confirmation_password){
            if($password == $confirmation_password)
                return true;
            return false;
        }
        
        function saltPassword($password){
            //CONCATINATES PASSWORD TO RANDOMLY GENERATED NUMBER
            $result = array();
            $salt = rand(0, 1000);
            $saltedPw = $password . $salt;
            array_push($result, md5($saltedPw));
            array_push($result, $salt);
            return $result;
        }
        
        $username = cleanString($_POST['username']);
        $sql = "SELECT * FROM `Users` WHERE username='$username'";
        $query = mysqli_query($link, $sql);
        
        //VALIDATION BLOCK
        while($row = mysqli_fetch_array($query)){
            die("<i>An error occured</i><br>username already taken");
        }
        if(validateEmail(cleanString($_POST['email'])) == false){
            die("<i>An error occured</i><br>invalid email");
        }
        if(validatePasswords(cleanString($_POST['password']), cleanString($_POST['cpassword'])) == false){
            die("<i>An error occured</i><br>password mismatch");
        }
        if(validateName(cleanString($_POST['username'])) == false){
            die("<i>An error occured</i><br>invalid username");
        }
        if(validateName(cleanString($_POST['firstname'])) == false){
            die("<i>An error occured</i><br>invalid firstname");
        }
        if(validateName(cleanString($_POST['lastname'])) == false){
            die("<i>An error occured</i><br>invalid lastname");
        }
        //END OF VALIDATION BLOCK 

        $isAdmin = 0;
        $salt = saltPassword($_POST['password']);
        $password = $salt[0];
        $salt = $salt[1];
        $email = cleanString($_POST['email']);
        $username = cleanString($_POST['username']);
        $firstname = cleanString($_POST['firstname']);
        $lastname = cleanString($_POST['lastname']);
        $certificate = cleanString($_POST['certificate']);
        if(cleanString($_POST['isAdmin']) == "true"){
            $isAdmin == 1;
        }
        
        $date = date("Y/m/d H:i ").date_default_timezone_get();        
        $sql = "INSERT INTO `Users` (first_name, last_name, username, email, CertificateID, isAdmin, failed_attempts, last_login, password_digest, salt) VALUES ('$firstname', '$lastname', '$username', '$email', '$certificate', '$isAdmin', 0, '$date', '$password', '$salt')";
        mysqli_query($link, $sql);
        //REGISTRATION SUCCESSFUL AT THIS POINT
        
        $_POST['password'] = md5($_POST['password']);
        $_POST['cpassword'] = md5($_POST['cpassword']);
        
        echo "<style>a{color: inherit; text-decoration: none;}</style>";
        echo "<br><span style='color: green;'>Registration Successful</span><br><br>";
        echo "<b>Details:</b><br>".json_encode($_POST)."<br><br>";
        echo "<span style='margin: 20px 0 0 2px;'></span><a href='login.php' style='color: blue; text-decoration: underline;'>Log in</a>";
    }
    
    
    
    
