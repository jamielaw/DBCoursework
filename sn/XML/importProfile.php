<?php

require '../session.php';


$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/XML';
$path_parts = pathinfo($_FILES["fileToUpload"]["name"]);
if(strcmp($path_parts['extension'],'xml')==0)
{
    $path = $path_parts['filename'].'.'.$path_parts['extension'];
    //echo $path;
    $ok=1;
} else {
    echo "Sorry, the file you uploaded is not an XML file.";
}


if ($ok)
{
    //Getting XML file
    //$path = 'mydb-backup-1487788223.xml';
    if (file_exists($path)) {
        $xml = new SimpleXMLElement($path, 0, true);
        //echo $xml->asXML();
    } else {
        exit('Failed to open xml file.');
    }

    function getDBName($xml)
    {
        return $xml['name'];
    }

    //Get Records
    function getRecords($xml, $tableName)
    {
        $databaseName=getDBName($xml);
        $sqlQuery=null;
        $records_exist=0;
        foreach ($xml->user->table as $table) { //iterate tables
            if ($table['name']==$tableName) {
                //echo $tableName.' ';
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
        //echo $sqlQuery.' ';
        return $sqlQuery;
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

    function getUser($xml)
    {
        return $xml->user['name'];
    }

    function deleteUserData($email){

        $pdo = Database::connect_fordrop();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $accessrights = 'DELETE FROM mydb.accessrights WHERE email=\''.$email.'\' OR (circleFriendsId IN (SELECT circleFriendsId FROM mydb.usercirclerelationships WHERE email=\''.$email.'\')) OR (photoCollectionId IN (SELECT photoCollectionId FROM mydb.photocollection WHERE createdBy=\''.$email.'\'))';

        $annotations = 'DELETE FROM mydb.annotations WHERE email = \''.$email.'\' OR photoId IN (SELECT photoId FROM mydb.photos WHERE photoCollectionId IN (SELECT photoCollectionId FROM mydb.photocollection WHERE createdBy=\''.$email.'\'))';

        $comments = 'DELETE FROM mydb.comments WHERE email=\''.$email.'\' OR photoId IN (SELECT photoId FROM mydb.photos WHERE photoCollectionId IN (SELECT photoCollectionId FROM mydb.photocollection WHERE createdBy=\''.$email.'\'))';

        $photos = 'DELETE FROM mydb.photos WHERE photoCollectionId IN (SELECT photoCollectionId FROM mydb.photocollection WHERE createdBy=\''.$email.'\')';

        $posts = 'DELETE FROM mydb.posts WHERE blogId IN (SELECT blogId FROM mydb.blogs WHERE email=\''.$email.'\')';

        $blogs = 'DELETE FROM mydb.blogs WHERE email=\''.$email.'\'';

        $usercirclerelationships = 'DELETE FROM mydb.usercirclerelationships WHERE email=\''.$email.'\'';

        $friendships = 'DELETE FROM mydb.friendships WHERE emailFrom=\''.$email.'\' OR emailTo=\''.$email.'\'';

        $messages = 'DELETE FROM mydb.messages WHERE emailTo=\''.$email.'\' OR emailFrom=\''.$email.'\'';

        $photoCollectionId = 'DELETE FROM mydb.photocollection WHERE createdBy=\''.$email.'\'';

        $privacySettings = 'DELETE FROM mydb.privacySettings WHERE email=\''.$email.'\'';

        $users = 'DELETE FROM mydb.users WHERE email=\''.$email.'\'';

        $deletingRecords = [ 
            $accessrights,
            $annotations,
            $comments,
            $photos,
            $posts,
            $blogs,
            $friendships,
            $usercirclerelationships,
            $messages,
            $photoCollectionId,
            $privacySettings,
            $users
        ];

        foreach ($deletingRecords as $sqlquery) {
            //echo nl2br("\n"); //Line break in HTML conversion
        //echo "<b>Executing SQL statement: </b>";
          //echo $sqlquery; //Dispay statement being executed
        //echo nl2br("\n");
            $q= $pdo->prepare($sqlquery);
            if ($q->execute() === true) {
                //echo "<b><font color='green'>SQL statement performed correctly</b></font>";
            } else {
               // echo "<b><font color='red'>Error executing statement: </b></font>" . $pdo->error;
            }
        }
    }

    $email = getUser($xml);
    $error=0;
    if(strcmp($email,$loggedInUser)!=0)
        $error=1;
    //echo $email;

    if($error)
    {
        echo "You are trying to import someone else's profile or the profile you are trying to import doesn't exist! Please make sure you import only your OWN profile.";
    } else {
        deleteUserData($email);
        $tables_order = array();
        array_push($tables_order, 'users', 'usercirclerelationships', 'friendships', 'privacysettings', 'photocollection', 'photos', 'annotations', 'accessrights', 'comments', 'blogs', 'posts', 'messages');

        $populateDatabase=[];
        $sqlQueryArray=createRecords($xml, $tables_order);
        foreach ($sqlQueryArray as $value) {
            //echo $value.'<br/><br/>';
            array_push($populateDatabase, $value);
        }

        $pdo = Database::connect_fordrop();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        foreach ($populateDatabase as $sqlquery) {
            //echo nl2br("\n"); //Line break in HTML conversion
          //echo "<b>Executing SQL statement: </b>";
            //echo $sqlquery; //Dispay statement being executed
          //echo nl2br("\n");
            $q= $pdo->prepare($sqlquery);
            if ($q->execute() === true) {
                //echo "<b><font color='green'>SQL statement performed correctly</b></font>";
            } else {
                //echo "<b><font color='red'>Error executing statement: </b></font>" . $pdo->error;
            }
        }
        echo "Profile imported successfully!";
        Database::disconnect();
    }
}