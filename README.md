# csv_to_mysql
Automatically load a CSV into a MySQL table

This script will read a csv file with headings in the first row and convert the whole file into a MySQL table of varchar fields.

To run the script you will need to adjust the settings of the parameters below in the PHP file.

`$field_length_padding 	= 10;`

Can be any number from 0 upwards and will pad the max field length by the number of chars indicated

`$import_filename 		= 'example_file.csv';`

Name of the file to import. This will be in current folder. Can include full path or subdirectory.

`$new_table_name			= 'example_table';`

Name of the table that will be created.

`$database_location		= 'localhost';
$database_user			= 'mysql_user';
$database_password		= 'mysql_password';
$database_name 			= 'example_database';`

Database details - modify as necessary

`$drop_table_if_exists	= true;`

This adds a drop command to the query so that if the table exists it removes to allow full reimport of the data

`$check_max_lenghths 	= true;`

Do you want the system to run through the whole file to derive the correct field lengths?

`$load_data 				= true;`

Do you want it to not only create the tables but load the data as well? 
