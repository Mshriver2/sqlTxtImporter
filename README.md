# sqlTxtImporter
Imports password text files into sql tables using php.

Example data:
test01@example.com:test11
test02@example.com:test22
test03@example.com:test33

This would put the items on the left into the email column and the items on the right into the password column.

## TODO

* ~~fix email / username importation regex~~
* ~~create file upload box~~
* ~~create option to choose email / username and password separator symbol~~
* create an api to convert password tables to json format
* create a loading / status bar
* allow multiple files in a row
