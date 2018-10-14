<?
    define('DB_NAME', 'PesticideCtrlDB');
    define('DB_USER', 'comp2190SA');
    define('DB_PASSWORD', '2017Sem1');
    define('DB_HOST', '127.0.0.1');
    
    //CONNECTS TO DATABASE
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    //DEFINES A CONNECTION CONSTANT
    define("DBconnection", json_encode($link));
    
    if(!$link){
        //CONNECTION FAILED
        die('Could not connect to database: ' . mysql_error());
    }
    
    $selectedb = mysqli_select_db($link, DB_NAME);
    
    if(!$selectedb){
        die('Could not select database: ' . mysql_error());
    }  
