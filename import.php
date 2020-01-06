<?php

// ^([^:]+)

define("BR", "<br>");

//path to txt file, as well as name used for db table
//$file = "./10.txt";

//used to extract the passwords out of the text file


//function get_passwords($file){
    //$file_path = "./uploads/".$file;
    //$file_contents = file_get_contents($file_path);

    //use if seperated by :
    //preg_match_all("/(?<=\:).*/", $file_contents, $pass_array);

    //use if seperated by ;
    //preg_match_all("/(?<=\;).*/", $file_contents, $pass_array);

    //print_r($pass_array);
    //return $pass_array;
//}


//used to extract the usernames or emails out of the text file
//function get_users($file){

    //$file_path = "./uploads/".$file;
    //$file_contents = file_get_contents($file_path);

    //preg_replace("/(?<=\:).*/", $file_contents, "", $user_array_pre);
    //preg_match_all("/(.*?)/", $user_array_pre, $user_array);

    //print_r($user_array).BR;
    //return $user_array;

//}

//imports the info from the user and pass functions to the database
function creds_db_import($file){

    //get_users($file);
    //get_passwords($file);

    //$tmp_user_array = get_users($file);
    //$tmp_pass_array = get_passwords($file);

    $file_path = "./uploads/".$file;
    print_r($file_path);

    $file_contents = file_get_contents($file_path);
    print_r($file_contents);

    $file = preg_split("/[:]/", $file_contents);
    print_r($file);

    //$step1 = str_replace(".","",$file);
    //$newname = str_replace("/","",$step1);
    //echo $newname.BR;

    //print_r($tmp_user_array);
    $newname = "array";
    //counts the number of elements in the password array
    $totalPasswords = count($file);
    echo $totalPasswords." Total Passwords".BR;


    //conects to database
    require_once "db.php";

    $db = mysqli_connect($ip,$user,$password,$table);

    //checks if db to connection failed
    if (!$db) {
    echo "Error: Unable to connect to MySQL.".BR;
    echo "Debugging errno: " . mysqli_connect_errno().BR;
    echo "Debugging error: " . mysqli_connect_error().BR;
    exit;
    }

    //if connection succeeds
    echo "Success: A proper connection to MySQL was made! The my_db database is great.".BR;
    echo "Host information: " . mysqli_get_host_info($db).BR;

    //checks if sql table with the name $file exists
    if ( mysqli_query( "DESCRIBE $newname" ) ) {

        //if the table exists
        echo "the text file already exists in the db".BR;
    }else{

        //if the table does not exist
        echo "the table does not exist";
        //creates the sql table
        mysqli_query($db, "CREATE TABLE $newname (email_or_user VARCHAR(255) NOT NULL,password VARCHAR(255) NOT NULL)");

        //runs sql query as many times as the total amount of passwords
        for ($i=0; $i < $totalPasswords; $i++) {

            //$tmp_user = $tmp_user_array[0][$i];
            //$tmp_pass = $tmp_pass_array[0][$i];
            //print_r($details).BR;
            
            if (($i % 2) == 0) {
                mysqli_query($db, "INSERT INTO $newname (email_or_user) VALUES ('$file[$i]')");
                print_r($file[$i]);
            }
            if (($i % 2) == 1) {
                mysqli_query($db, "INSERT INTO $newname (password) VALUES ('$file[$i]')");
                print_r($file[$i]);
            }
        
        }

        echo "import ran".BR;

    }



}

function uploadFile() {
    if(!empty($_FILES['fileToUpload'])){
    $path = "./uploads/";
    $path = $path . basename( $_FILES['fileToUpload']['name']);
    if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $path))
    {
      echo "The file ".basename( $_FILES['fileToUpload']['name'])." has been uploaded".BR;
    } else{
      echo "There was an error uploading the file, please try again!<$BR />".BR;
    }
  }
    $uploadedFile = $_FILES['fileToUpload']['name'];
    return $uploadedFile;
}

if (isset($_POST['submit'])) {
    $file = uploadFile();
    creds_db_import($file);
}


 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>SQLtxt</title>
    </head>
    <body>
        <h1>SQL txt data importer</h1>
        <form method="post" enctype="multipart/form-data">
            Select file to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload File" name="submit">
        </form>
    </body>
</html>
