<?php
$host = 'localhost';
$user = 'root';
$pass = 'root';
$name = 'mydb';
$email = null;


require("../database.php");

if ( !empty($_POST['email'])) {
  $email = $_POST['email'];
}


function getConstraints($table, $columnName)
{
    $getConstraints = 'SELECT cols.TABLE_NAME AS INITIAL_TABLE_NAME, cols.COLUMN_NAME AS INITIAL_COLUMN_NAME, cols.ORDINAL_POSITION,
  cols.COLUMN_DEFAULT, cols.IS_NULLABLE, cols.DATA_TYPE,
  cols.CHARACTER_MAXIMUM_LENGTH, cols.CHARACTER_OCTET_LENGTH,
  cols.NUMERIC_PRECISION, cols.NUMERIC_SCALE,
  cols.COLUMN_TYPE, cols.COLUMN_KEY, cols.EXTRA,
  cols.COLUMN_COMMENT, refs.REFERENCED_TABLE_NAME, refs.REFERENCED_COLUMN_NAME,
  cRefs.UPDATE_RULE, cRefs.DELETE_RULE,
  links.TABLE_NAME, links.COLUMN_NAME,
  cLinks.UPDATE_RULE, cLinks.DELETE_RULE
  FROM INFORMATION_SCHEMA.`COLUMNS` as cols
  LEFT JOIN INFORMATION_SCHEMA.`KEY_COLUMN_USAGE` AS refs
  ON refs.TABLE_SCHEMA=cols.TABLE_SCHEMA
  AND refs.REFERENCED_TABLE_SCHEMA=cols.TABLE_SCHEMA
  AND refs.TABLE_NAME=cols.TABLE_NAME
  AND refs.COLUMN_NAME=cols.COLUMN_NAME 
  LEFT JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS AS cRefs
  ON cRefs.CONSTRAINT_SCHEMA=cols.TABLE_SCHEMA
  AND cRefs.CONSTRAINT_NAME=refs.CONSTRAINT_NAME
  LEFT JOIN INFORMATION_SCHEMA.`KEY_COLUMN_USAGE` AS links
  ON links.TABLE_SCHEMA=cols.TABLE_SCHEMA
  AND links.REFERENCED_TABLE_SCHEMA=cols.TABLE_SCHEMA
  AND links.REFERENCED_TABLE_NAME=cols.TABLE_NAME
  AND links.REFERENCED_COLUMN_NAME=cols.COLUMN_NAME
  LEFT JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS AS cLinks
  ON cLinks.CONSTRAINT_SCHEMA=cols.TABLE_SCHEMA
  AND cLinks.CONSTRAINT_NAME=links.CONSTRAINT_NAME
  WHERE cols.TABLE_SCHEMA= \'mydb\'
  AND cols.TABLE_NAME= ?
  AND cols.COLUMN_NAME= ?;';

    $pdo = Database::connect();
    $q1 = $pdo->prepare($getConstraints);
    $q1->execute(array($table,$columnName));
    $constraints = null;
    foreach ($q1->fetchAll() as $row) {
        if ($columnName==$row['INITIAL_COLUMN_NAME']) {

            $constraints = array(
              0 => $row['IS_NULLABLE'],
              1 => $row['COLUMN_TYPE'],
              2 => $row['COLUMN_KEY'],
              3 => $row['EXTRA'],
              4 => $row['REFERENCED_TABLE_NAME'],
              5 => $row['REFERENCED_COLUMN_NAME'],
            );
        }
    }
    return $constraints;
}

//connect
$link = mysqli_connect($host, $user, $pass);
mysqli_select_db($link, $name);

//get all the tables
$query = 'SHOW TABLES FROM '.$name;
$result = mysqli_query($link, $query) or die('cannot show tables');
if (mysqli_num_rows($result)) {
    //prep output
    $tab = "\t"; 
    $br = "\n";
    $xml = '<?xml version="1.0" encoding="UTF-8"?>'.$br;
    $xml.= '<database name="'.$name.'">'.$br;
    $xml.= '<user name="'.$email.'">'.$br;

    //for every table...
    while ($table = mysqli_fetch_row($result)) {
        //prep table out
        $xml.= $tab.'<table name="'.$table[0].'">'.$br;

        // getting columns
        // table attributes
        $attributes = array('name','orgname','max_length','length','charsetnr','flags','type','decimals');
        $xml.= $tab.$tab.'<columns>'.$br;
        $x = 0;
        //get the rows
        $query = 'SELECT * FROM '.$table[0];
        $records = mysqli_query($link, $query) or die('cannot select from table: '.$table[0]);
        while ($x < mysqli_num_fields($records)) {
            $meta = mysqli_fetch_field($records);
            $xml.= $tab.$tab.$tab.'<column ';

            foreach ($attributes as $attribute) {
                if ($attribute=='name') {
                    $columnName = $meta->$attribute;
                }
                $xml.= $attribute.'="'.$meta->$attribute.'" ';
            }
            $constraints = getConstraints($table[0], $columnName);
            if ($constraints) {
                $xml.= 'is_nullable="'.$constraints[0].'" ';
                $xml.= 'column_type="'.$constraints[1].'" ';
                if ($constraints[2]) {
                    $xml.= 'column_key="'.$constraints[2].'" ';
                }
                if ($constraints[3]) {
                    $xml.= 'extra="'.$constraints[3].'" ';
                }
                if ($constraints[4]) {
                    $xml.= 'referenced_table_name="'.$constraints[4].'" ';
                }
                if ($constraints[5]) {
                    $xml.= 'referenced_column_name="'.$constraints[5].'" ';
                }
            }

            $xml.= '/>'.$br;
            $x++;
        }
       // echo '<br/><br/>';
        $xml.= $tab.$tab.'</columns>'.$br;

        // getting rows (records)
        if(strcmp($table[0],"accessrights")==0)
        {
          // 1. get the user's access rights
          // 2. get the access rights of the user's photo collection 
          // 3. get the access rights of the circles the user belongs to
          $query = 'SELECT * FROM accessrights WHERE email=\''.$email.'\' OR (circleFriendsId IN (SELECT circleFriendsId FROM usercirclerelationships WHERE email=\''.$email.'\')) OR (photoCollectionId IN (SELECT photoCollectionId FROM photocollection WHERE createdBy=\''.$email.'\'))';
        }
        if(strcmp($table[0], 'annotations')==0)
        {
          // 1. get the annotations made by the user
          // 2. get the annotations made on user's photos
          $query = 'SELECT * FROM annotations WHERE email=\''.$email.'\' OR (photoId IN (SELECT photoId FROM photos WHERE photoCollectionId IN (SELECT photoCollectionId FROM photocollection WHERE createdBy=\''.$email.'\')))';
        }
        if(strcmp($table[0], 'blogs')==0)
        {
          $query = 'SELECT * FROM blogs WHERE email=\''.$email.'\'';
        } 
        if(strcmp($table[0], 'circleoffriends')==0)
        {
          $qury = 'SELECT * FROM circleoffriends WHERE circleFriendsId IN (SELECT circleFriendsId FROM usercirclerelationships WHERE email = \''.$email.'\')';
        }
        if(strcmp($table[0], 'comments')==0)
        {
          // 1. get user's comments
          // 2. get other users' comments to the user's pictures
          $query = 'SELECT * FROM comments WHERE email=\''.$email.'\' OR photoId IN (SELECT photoId FROM photos WHERE photoCollectionId IN (SELECT photoCollectionId FROM photocollection WHERE createdBy=\''.$email.'\'))';
        }
        if(strcmp($table[0], 'friendships')==0)
        {
          $query = 'SELECT * FROM friendships WHERE emailFrom=\''.$email.'\' OR emailTo=\''.$email.'\'';
        }
        if(strcmp($table[0], 'messages')==0)
        {
          $query = 'SELECT * FROM messages WHERE (emailTo=\''.$email.'\' OR emailFrom=\''.$email.'\')';
        }
        if(strcmp($table[0], 'photocollection')==0)
        {
          $query = 'SELECT * FROM photocollection WHERE createdBy=\''.$email.'\'';
        }
        if(strcmp($table[0], 'photos')==0)
        {
          $query = 'SELECT * FROM photos WHERE photoCollectionId IN (SELECT photoCollectionId FROM photocollection WHERE createdBy=\''.$email.'\')';
        }
        if(strcmp($table[0], 'posts')==0)
        {
          $query = 'SELECT * FROM posts WHERE blogId IN (SELECT blogId FROM blogs WHERE email=\''.$email.'\')';
        }
        if(strcmp($table[0], 'privacysettings')==0)
        {
          $query = 'SELECT * FROM privacysettings WHERE email=\''.$email.'\'';
        }
        if(strcmp($table[0], 'usercirclerelationships')==0)
        {
          $query = 'SELECT * FROM usercirclerelationships WHERE email=\''.$email.'\'';
        }
        if(strcmp($table[0], 'users')==0)
        {
          $query = 'SELECT * FROM users WHERE email=\''.$email.'\'';
        }


          $records = mysqli_query($link, $query) or die('cannot select from table: '.$table[0]);

          //stick the records
          $xml.= $tab.$tab.'<records>'.$br;
          while ($record = mysqli_fetch_assoc($records)) {
              $xml.= $tab.$tab.$tab.'<record>'.$br;
              foreach ($record as $key=>$value) {
                  $xml.= $tab.$tab.$tab.$tab.'<'.$key.'>'.htmlspecialchars(stripslashes($value)).'</'.$key.'>'.$br;
              }
              $xml.= $tab.$tab.$tab.'</record>'.$br;
          }
          $xml.= $tab.$tab.'</records>'.$br;
          $xml.= $tab.'</table>'.$br;

    }
    $xml.= '</user>';
    $xml.= '</database>';

    //save file
    $fileName = $name.'-backup-'.time().'.xml';

    //on server
    $handle = fopen($fileName, 'w+');
    fwrite($handle, $xml);
    fclose($handle);

    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename="'.$fileName.'" ');

    //locally
    readfile($fileName);
    //exit();
    
    //echo 'Exported Successfully! The file will be opened in a word document.';

}
