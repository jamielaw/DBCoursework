<?php

//Getting XML file
$path = 'mydb-backup-1486313758.xml';
if (file_exists($path)) {
    $xml = new SimpleXMLElement($path, 0, true);
    //echo $xml->asXML();
} else {
    exit('Failed to open xml file.');
}

//Establising the order in which the tables need to be created in order to assure there are no conflicts when applying the column constraints
$tables_order = array(
    0 => "roles",
    1 => "users",
    2 => "rights",
    3 => "friendships",
    4 => "blogs",
    5 => "posts",
    6 => "privacysettings",
    7 => "circleoffriends",
    8 => "messages",
    9 => "usercirclerelationships",
    10 => "photocollection",
    11 => "photos",
    12 => "comments",
    13 => "annotations",
    14 => "accessrights",
);

function getDBName($xml)
{
    return $xml['name'];
}

function getTableConstraints($xml, $tableName)
{
    $databaseName=getDBName($xml);
    $sqlQuery=null;
    foreach ($xml->table as $table) { //iterate tables
        if ($table['name']==$tableName) {
            //echo 'TABLE_NAME: '.$table['name'].'<br/>', PHP_EOL;
            $foreign_key=null;
            $primary_key=null;
            foreach ($table->columns->column as $column) {//iterate constraints
              /*
                echo 'name: '.$column['name'].'<br/>', PHP_EOL;
                echo 'is_nullable: '.$column['is_nullable'].'<br/>', PHP_EOL;
                echo 'column_key: '.$column['column_key'].'<br/>', PHP_EOL;
                echo 'column_type: '.$column['column_type'].'<br/>', PHP_EOL;
                echo 'column_key: '.$column['name'].'<br/>', PHP_EOL;
                echo 'extra: '.$column['extra'].'<br/>', PHP_EOL;
                echo 'referenced_table_name: '.$column['referenced_table_name'].'<br/>', PHP_EOL;
                echo 'referenced_column_name: '.$column['referenced_column_name'].'<br/>', PHP_EOL;
                */
                if ($sqlQuery!=null) {
                    $sqlQuery=$sqlQuery.', ';
                } else {
                    $sqlQuery='CREATE TABLE IF NOT EXISTS '.$databaseName.'.'.$table['name'].'(';
                }
                $sqlQuery=$sqlQuery.$column['name'].' '.$column['column_type'];
                if ($column['is_nullable']=='NO') {
                    $sqlQuery=$sqlQuery.' NOT NULL';
                }
                if ($column['extra']=='auto_increment') {
                    $sqlQuery=$sqlQuery.' AUTO_INCREMENT';
                }
                if ($column['column_key']=='PRI') {
                    $primary_key='PRIMARY KEY('.$column['name'].')';
                }
                if ($foreign_key!=null&&$column['referenced_table_name']!=null) {
                    $foreign_key=$foreign_key.', ';
                }
                if ($column['referenced_table_name']!=null) {
                    $foreign_key='FOREIGN KEY('.$column['name'].') REFERENCES '.$databaseName.'.'.$column['referenced_table_name'].'('.$column['referenced_column_name'].')';
                }
            }
            if ($primary_key!=null) {
                $sqlQuery=$sqlQuery.', '.$primary_key;
            }
            if ($foreign_key!=null) {
                $sqlQuery=$sqlQuery.', '.$foreign_key;
            }
            $sqlQuery=$sqlQuery.')';
        }
    }
    return $sqlQuery;
}

function createTable($xml, $tables_order)
{
    $sqlQueryArray=array();
    foreach ($tables_order as $value) {
        $result = getTableConstraints($xml, $value);
        array_push($sqlQueryArray, $result);
    }
    return $sqlQueryArray;
}

require '../database.php';
$pdo = Database::connect_fordrop();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$name=getDBName($xml);
$storageEngine = "SET default_storage_engine = INNODB";
$dropDatabase = "DROP DATABASE IF EXISTS $name";
$createDatabase = "CREATE DATABASE IF NOT EXISTS $name";

$creatingTables = [ //make sure you create in the right order! foreign keys must refer to a primary key in an existing table
    $storageEngine,
    $dropDatabase, //uncomment this if there is a wrong format in any table
    $createDatabase,
];

$sqlQueryArray=createTable($xml, $tables_order);
foreach ($sqlQueryArray as $value) {
    echo $value.'<br/><br/>';
    array_push($creatingTables, $value);
}

foreach ($creatingTables as $sqlquery) {
    echo nl2br("\n"); //Line break in HTML conversion
  echo "<b>Executing SQL statement: </b>";
    echo $sqlquery; //Dispay statement being executed
  echo nl2br("\n");
    $q= $pdo->prepare($sqlquery);
    if ($q->execute() === true) {
        echo "<b><font color='green'>SQL statement performed correctly</b></font>";
    } else {
        echo "<b><font color='red'>Error executing statement: </b></font>" . $pdo->error;
    }
}
Database::disconnect();
