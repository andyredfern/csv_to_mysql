<?php
// create database table in mysql from a csv

//error reporting set up
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//constants
$field_length_padding 	= 10;
$import_filename 		= 'example_file.csv';
$new_table_name			= 'example_table';
$database_location		= 'localhost';
$database_user			= 'mysql_user';
$database_password		= 'mysql_password';
$database_name 			= 'example_database';
$drop_table_if_exists	= true;
$check_max_lenghths 	= true;
$load_data 				= true;

//script data storage
$column_widths = array();

// open file
$handle = fopen($import_filename , "r");

// Read first (headers) record only)
$data = fgetcsv($handle, 1000, ",");

//store the column names
foreach($data as $column) {
	$columns[]=$column;
	if($check_max_lenghths) $column_widths[]=0;
}

//check the column widths across all data
if($check_max_lenghths) {
	while(($data=fgetcsv($handle, 10000, ","))!==false) {
		$field_counter =0;
		foreach($data as $column){
			$field_length = strlen($column);
			if($field_length > $column_widths[$field_counter]) $column_widths[$field_counter] = $field_length;
			$field_counter++;
		}
	}
}

//build the query
$sql='';

if($drop_table_if_exists) {
	$sql .= 'drop table if exists ' . '`' . $new_table_name . '`;';
}

$sql .= 'CREATE TABLE `'.$new_table_name . '` (';

for($i=0;$i<count($columns); $i++) {
	$sql .= '`' .$columns[$i].'`'. ' VARCHAR('.$column_widths[$i].'), ';
}

$sql = substr($sql,0,strlen($sql)-2);
$sql .= ')';


//open db
$mysqli = new mysqli($database_location, $database_user, $database_password, $database_name);

echo $sql;

$mysqli->query($sql);

rewind($handle);

//load columns
$data = fgetcsv($handle, 1000, ",");

$sql_intro = "insert into `" .$new_table_name. '` (';

foreach($data as $column) {
	$sql_intro .= '`' . $column . '`,';
}
$sql_intro = substr($sql_intro,0,strlen($sql_intro)-1);

$sql_intro .= ") VALUES (";
$row=0;
if($load_data) {
	while(($data=fgetcsv($handle, 10000, ","))!==false) {
		$sql= $sql_intro;
		foreach($data as $column){
			$sql .= "'" . $column . "', ";
		}
		$sql = substr($sql,0,strlen($sql)-2);
		$sql .= ')';
		echo $sql ."<br><br>";
		$mysqli->query($sql);
		if($row++>10) break;
	}
}

fclose($handle);
?>