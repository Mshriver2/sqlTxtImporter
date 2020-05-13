<?php

// ^([^:]+)

define("BR", "<br>");



//imports the info from the user and pass functions to the database
function creds_db_import($file){

    // sets the location of the .txt files
    $file_path = "./uploads/".$file;
    print_r($file_path).BR;

    $file_contents = file_get_contents($file_path);
    //print_r($file_contents);

    $fn = fopen($file_path,"r");

    $final_array = array();

    //sets value for preg_split username and passwords
    $symbol = $_POST['seperator'];

    //goes through each line of the file one at a time
    while(! feof($fn))  {
	       $result = fgets($fn);
           $new = preg_split("/[$symbol]/", $result);

           //adds split value to the array
	       array_push($final_array, $new);
          }

    fclose($fn);

    // removes any dots or slashes from the filename for use as the sql table name
    $step1 = str_replace(".","",$file);
    $newname = str_replace("/","",$step1);
    echo $newname.BR;

    //print_r($tmp_user_array);

    //counts the number of elements in the password array
    $totalPasswords = count($final_array);
    echo $totalPasswords." Total Passwords".BR;


    //conects to database
    require "db.php";

    $db = mysqli_connect($ip,$user,$password,$table);

    //checks if db to connection failed
    if (!$db) {
    echo "Error: Unable to connect to MySQL.".BR;
    echo "Debugging errno: " . mysqli_connect_errno().BR;
    echo "Debugging error: " . mysqli_connect_error().BR;
    exit;
    }

    //if connection succeeds
    echo "Success: A proper connection to MySQL was made!".BR;
    echo "Host information: " . mysqli_get_host_info($db).BR;

    //checks if sql table with the name $file exists
    if ( mysqli_query( "DESCRIBE $newname" ) ) {

        //if the table exists
        echo "the text file already exists in the db".BR;
    }else{

        //if the table does not exist
        echo "the table does not exist".BR;
        //creates the sql table
        mysqli_query($db, "CREATE TABLE $newname (email_or_user VARCHAR(255) NOT NULL,password VARCHAR(255) NOT NULL)");

        //runs sql query as many times as the total amount of passwords
        for ($i=0; $i < $totalPasswords; $i++) {

                $tmp_user = $final_array[$i][0];
                $tmp_pass = $final_array[$i][1];

                mysqli_query($db, "INSERT INTO $newname (email_or_user, password) VALUES ('$tmp_user','$tmp_pass')");


        }

        echo "import ran".BR;
        mysqli_close($db);

    }
}



function uploadFile($user_arr) {

    print_r($user_arr);

    $fileCount = count($user_arr['name']);

    $progressCount = 1;

    for ($i = 0; $i<$fileCount; $i++){

        if(!empty($_FILES['userfile'])){
        $path = "./uploads/";
        $path = $path . basename( $_FILES['userfile']['name'][$i]);
        if(move_uploaded_file($_FILES['userfile']['tmp_name'][$i], $path))
        {
          echo "The file ".basename( $_FILES['userfile']['name'][$i])." has been uploaded".BR;

          $file = $user_arr['name'][$i];
          creds_db_import($file);
          $progress = $progressCount / $fileCount;
          $progress++;

        } else{
          echo "There was an error uploading the file, please try again!<$BR />".BR;
        }
      }

    }


    $uploadedFile = $_FILES['userfile']['name'][$i];
    return $uploadedFile;
}



if (isset($_POST['submit'])) {
    $user_arr = $_FILES['userfile'];
    $userFile = uploadFile($user_arr);

}


 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>SQLtxt</title>
    </head>
    <body class="Site">
        <div class="Site-content">


        <h1>Text to SQL Table Importer</h1>
        <form method="post" enctype="multipart/form-data">
            <h2>Select file to upload:</h2>
             <br><input type="file" name="userfile[]" id="fileToUpload" multiple="">
             <h2>Enter a seperator<h2><input type="text" name="seperator" value=""><br>
             <input type="submit" value="Upload File" name="submit">

        </form>

        <h2>Progress: <?php echo $progress;?></h2>

        </div>

        <div class="footer">
            <p>©2020 Keker, LLC</p>
        </div>

    </body>
</html>
