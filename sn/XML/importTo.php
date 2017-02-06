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
            $nr_of_pk=0;
            $nr_of_fk=0;
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
                    if ($nr_of_pk==0) {
                        $primary_key='PRIMARY KEY('.$column['name'];
                        $nr_of_pk=1;
                    } else { //first PK
                      $primary_key=$primary_key.','.$column['name'];
                    }
                }
                if ($column['referenced_table_name']!=null) {
                    if ($nr_of_fk==0) {
                        $foreign_key='FOREIGN KEY('.$column['name'].') REFERENCES '.$databaseName.'.'.$column['referenced_table_name'].'('.$column['referenced_column_name'].')';
                        $nr_of_fk=1;
                    } else {
                        $foreign_key=$foreign_key.','.'FOREIGN KEY('.$column['name'].') REFERENCES '.$databaseName.'.'.$column['referenced_table_name'].'('.$column['referenced_column_name'].')';
                    }
                }
            }
            if ($primary_key!=null) {
                $sqlQuery=$sqlQuery.', '.$primary_key.')';
            }
            if ($foreign_key!=null) {
                $sqlQuery=$sqlQuery.', '.$foreign_key;
            }
            $sqlQuery=$sqlQuery.')';
        }
    }
    return $sqlQuery;
}

function getRecords($xml, $tableName)
{
    $databaseName=getDBName($xml);
    $sqlQuery=null;
    $records_exist=0;
    foreach ($xml->table as $table) { //iterate tables
        if ($table['name']==$tableName) {
            $columnNames=array();
            $columnTypes=array();
            foreach ($table->columns->column as $column) {
                array_push($columnNames, $column['name']);
                array_push($columnTypes, $column['column_type']);
            }

            $sqlQuery='INSERT INTO '.$databaseName.'.'.$table['name']. ' VALUES ';
            $ok2=0; //to keep track when to add comma between records
            foreach ($table->records->record as $record) {//iterate constraints
                if ($ok2==1) {
                    $sqlQuery=$sqlQuery.',';
                }
                $sqlQuery=$sqlQuery.'(';
                $ok=0; //to keep track when to add comma between elements in a record
                foreach ($columnNames as $key=>$column) {
                    $records_exist=1; //to keep track if the table doesn't have any records
                    $value=$record[0]->$column;
                    $value=str_replace('"', '\"', $value); //escape any quatations marks
                    $datatype=$columnTypes[$key];
                    if ($value=='') {
                        $value="null";
                    }

                    if ($ok==0) { //first element in record
                        if (strpos($datatype, "varchar") !== false || $datatype=="timestamp" || $datatype=="longtext") { //if element is varchar/date/timestamp/text add brackets
                            $sqlQuery=$sqlQuery.'"'.$value.'"';
                        } else {
                            $sqlQuery=$sqlQuery.''.$value;
                        }
                        $ok=1;
                    } else { //not first element in record => add comma before
                        if (strpos($datatype, "varchar") !== false || $datatype=="timestamp" || $datatype=="longtext") {
                            $sqlQuery=$sqlQuery.',"'.$value.'"';
                        } else {
                            $sqlQuery=$sqlQuery.','.$value;
                        }
                    }
                    $ok2=1;
                }
                $sqlQuery=$sqlQuery.')';
            }
            //echo $sqlQuery.'<br/><br/>';
        }
    }
    if ($records_exist==0) {
        $sqlQuery=0;
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

function createRecords($xml, $tables_order)
{
    $sqlQueryArray2=array();
    foreach ($tables_order as $value) {
        $result = getRecords($xml, $value);
        if ($result!='0') {
            array_push($sqlQueryArray2, $result);
        }
    }
    return $sqlQueryArray2;
}


require '../database.php';
$pdo = Database::connect_fordrop();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$name=getDBName($xml);
$storageEngine = "SET default_storage_engine = INNODB";
$dropDatabase = "DROP DATABASE IF EXISTS $name";
$createDatabase = "CREATE DATABASE IF NOT EXISTS $name";

$creatingDatabase = [ //make sure you create in the right order! foreign keys must refer to a primary key in an existing table
    $storageEngine,
    $dropDatabase, //uncomment this if there is a wrong format in any table
    $createDatabase,
];

//Creating Tables
$sqlQueryArray=createTable($xml, $tables_order);
foreach ($sqlQueryArray as $value) {
    array_push($creatingDatabase, $value);
}

//Populating Tables
$sqlQueryArray2=createRecords($xml, $tables_order);
foreach ($sqlQueryArray2 as $value) {
    echo $value.'<br/><br/>';
    array_push($creatingDatabase, $value);
}

foreach ($creatingDatabase as $sqlquery) {
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
