# sqlTxtImporter
Imports password text files into sql tables using php.

Example data:
test01@example.com:test11
test02@example.com:test22
test03@example.com:test33

This will create a table with the same name as you're text file. Then it will put the items on the left of you're separator symbol into the email column and the items on the right into the password column.

## Setup
Rename 'db-template.php' to 'db.php'. After that, insert you're database credentials into the 'db.php' file. You're password text files must be separated by a colon or semicolon.

## Example Screenshots
[logo]: http://thekeker.com/images/github_images/pic_1_txtsql.JPG "Before - text file"

[logo]: http://thekeker.com/images/github_images/pic_1_txtsql.JPG "After - sql database table"

## TODO

* use OOP to connect to and manage sql statements
* ~~fix email / username importation regex~~
* ~~create file upload box~~
* ~~create option to choose email / username and password separator symbol~~
* create an api to convert password tables to json format
* create a loading / status bar
* ~~allow multiple files in a row~~
