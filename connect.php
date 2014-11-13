<?php

    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'universitysync';
    // $Name = 'web1';


    $connect = mysql_connect($host, $user, $password) or die('Can\'t join to database');
    mysql_query("USE " . $database) or die("can't join to database ".mysql_error());
    //mysql_set_charset('utf8');
?>