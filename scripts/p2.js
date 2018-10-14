window.validateForm = function(){
    
    //ARRAY CONTAINING ALL FORM FIELDS
    fields = [
        window.document.getElementById('firstname'),
        window.document.getElementById('lastname'),
        window.document.getElementById('username'),
        window.document.getElementById('email'),
        window.document.getElementById('isAdmin'),
        window.document.getElementById('certificate'),
        window.document.getElementById('password'),
        window.document.getElementById('cpassword')
    ];
    
    for(var x = 0; x < fields.length; x++){
        //RESETS ALL FORM FIELDS TO DEFAULT COLOR BEFORE VALIDATION BEGINS
        fields[x].style.borderColor = "#00B0AA";
    }
    
    function setError(element){
        //SETS CLASS NAME OF ELEMENT IF ERROR IS DETECTED
        element.className = "errorField";
        element.style.borderColor = "red";
    }
    
    function setNoError(element){
        //SETS CLASS NAME OF ELEMENT
        element.className = "noErrorField";
        element.style.borderColor = "#00B0AA";        
    }
    
    //BOOLEAN USED FOR DETERMINING IF FORM SHOULD BE SUBMITTED
    var error = false;
    
    for(var x = 0; x < fields.length; x++){
        if(fields[x].value == ""){
            //IF FIELD IS EMPTY MAKE BORDER RED
            setError(fields[x]);
            error = true;
        }
        else{
            setNoError(fields[x]);
        }
    }
    
    if(error){
        alert("fill all fields")
    }
    


    //EMAIL VALIDATION
    validateEmail = function(email){
        if(email.value[0] == "@"){
            return false;
        }
        for(var x = 0; x < email.value.length; x++){
            if(email.value[x] == "@"){
                if(email.value[x+1]){
                    for(var y = x+1; y < email.value.length; y++){
                        if(email.value[y] == "."){
                            if(email.value[y+1]){
                                domainExtension = "";
                                for(var z = y+1; z < email.value.length; z++){
                                    domainExtension += email.value[z]
                                }
                                //if(domainExtension == "com" || domainExtension == "org" || domainExtension == "net"){
                                    return true;
                                //}
                            }
                        }
                    }     
                }
                else{
                    return false;
                }
            }
        }   
        return false;    
    }
    
    if(validateEmail(fields[3]) == false && error == false){
        error = true;
        alert("enter a valid email");
        setError(fields[3]);
    }
    
    if(window.document.getElementById('isAdminBox').checked){
        window.document.getElementById('isAdmin').value = "true";
    }
    else{
        window.document.getElementById('isAdmin').value = "false";
    }
    
    if(fields[6].value.length < 8 && fields[6].value.length > 0){
        window.alert("password is too short");
        fields[6].className = "errorField";
        error = true;
    }
    else{
        if(fields[6].value != fields[7].value){
            setNoError(fields[6]);
            error = true;
            alert("passwords do not match");
            fields[7].className = "errorField";
            setError(fields[7]);
        }
        else if (fields[6].value.length > 0){

            function isSpecialCharacter(str){
             return !/[~`!#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/g.test(str);
            }
            
            spchar = false;
            upcase = false;
            lowcase = false;
            hasnum = false;
            
            for(var x = 0; x < fields[6].value.length; x++){
                //CHECKING FOR AT LEAST ONE SPECIAL CHARACTER
                charact = fields[6].value[x];
                if(charact.charCodeAt(0) >= 65 && charact.charCodeAt(0) <= 90){
                    //UPPERCASE LETTER FOUND
                    upcase = true;
                }                
                if(charact.charCodeAt(0) >= 33 && charact.charCodeAt(0) <= 47 || charact.charCodeAt(0) >= 58 && charact.charCodeAt(0) <= 64 || charact.charCodeAt(0) >= 91 && charact.charCodeAt(0) <= 96 || charact.charCodeAt(0) >= 123 && charact.charCodeAt(0) <= 126){
                    //SPECIAL CHARACTER FOUND
                    spchar = true;
                }      
                if(charact.charCodeAt(0) >= 97 && charact.charCodeAt(0) <= 122){
                    //LOWERCASE LETTER FOUND
                    lowcase = true;
                }  
                if(isNaN(fields[6].value[x])  == false){
                    //NUMBER FOUND
                    hasnum = true;
                }                                      
                  
                if(x == fields[6].value.length - 1){
                    if(hasnum == false || lowcase == false || upcase == false || spchar == false){
                        error = true;   
                        if(hasnum == false){
                            alert("password should contain at least one number");
                        }  
                        if(lowcase == false){
                            alert("password should contain at least one lower case letter");
                        }  
                        if(upcase == false){
                            alert("password should contain at least one upper case letter");
                        }  
                        if(spchar == false){
                            alert("password should contain at least one special character");
                        }
                    }
                }
            }  
        }
        if(error == false){
            setNoError(fields[6]);
            setNoError(fields[7]);
        }
        else{
            setError(fields[6]);
        }
    }
                
    if(error == true){
        return;
    }
    else{
        window.document.getElementById("newUserForm").submit();
    }
}
