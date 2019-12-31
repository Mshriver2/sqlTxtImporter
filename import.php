<?php

//path to txt file, as well as name used for db table
$file = "./Poloniex.txt";

//used to extract the passwords out of the text file
function get_passwords($file){


    $file_contents = file_get_contents($file);

    //use if seperated by :
    preg_match_all("/(?<=\:).*/", $file_contents, $pass_array);

    //use if seperated by ;
    //preg_match_all("/(?<=\;).*/", $file_contents, $pass_array);



    return $pass_array;


}

//used to extract the usernames or emails out of the text file
function get_users($file){


    $file_contents = file_get_contents($file);

    preg_match_all("/^(.*)(?=:)/", $file_contents, $user_array);


    print_r($user_array);

}

//imports the info from the user and pass functions to the database
function creds_db_import($file){

    get_users($file);
    get_passwords($file);

    $tmp_pass_array = get_passwords($file);

    //preg_match("/\w+/",$file,$new_file_name);

    //$new_merged_name = array_merge($new_file_name);

    //echo $new_merged_name;

    $step1 = str_replace(".","",$file);
    $newname = str_replace("/","",$step1);
    echo $newname;

    //print_r($tmp_pass_array);

    //counts the number of elements in the password array
    $totalPasswords = array_sum(array_map("count", $tmp_pass_array));
    echo $totalPasswords;


    //conects to database
    require_once "db.php";
    $db = mysqli_connect($ip,$user,$password,$table);

    //checks if db to connection failed
    if (!$db) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
    }

    //if connection succeeds
    echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
    echo "Host information: " . mysqli_get_host_info($db) . PHP_EOL;

    //checks if sql table with the name $file exists
    if ( mysqli_query( "DESCRIBE $newname" ) ) {

        //if the table exists
        echo "the text file already exists in the db";
    }else{

        //if the table does not exist
        echo "the table does not exist";
        //creates the sql table
        $query9 = mysqli_query($db, "CREATE TABLE $newname (email_or_user VARCHAR(255) NOT NULL,password VARCHAR(255) NOT NULL)");

        //runs sql query as many times as the total amount of passwords
        for ($i=0; $i < $totalPasswords; $i++) {

            $tmp_pass = $tmp_pass_array[0][$i];
            $query10 = mysqli_query($db, "INSERT INTO $newname (email_or_user, password) VALUES ('test', '$tmp_pass')");
        }

        echo "import ran";

    }



}

creds_db_import($file);


 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>SQLtxt</title>
    </head>
    <body>
        <h1>SQL txt data importer</h1>
    </body>
</html>
