<?php
require 'connect.php';      
if (isset($_GET['userId'])) $userId = intval($_GET['userId']);
else $userId = $this->user['id'];
if (isset($_GET['groupId']))$groupId = intval($_GET['groupId']);
else $groupId = $this->group['id'];
if (isset($_GET['q'])) {
    header("Content-Type: text/html; charset=UTF-8");
    include 'stdlib.php';  
    $q = $_GET['q'];
} else {
    $q = "all";
}
if ($_GET['page'] == 'home') {       // USER
    $sql = "SELECT * FROM `file` WHERE id_user = {$this->user['id']} ";
    if (isset($_GET['filterIdGroup'])) $sql .= "AND id_group = '{$_GET['filterIdGroup']}' ";
    $sql .= "ORDER BY id DESC";
}
else if (isset ($_GET['userId']) && !isset($_GET['q'])) {  // GROUP
    $sql = "SELECT * FROM `file` WHERE id_user = '{$userId}'";
    if ($_GET['groupId'] != 'all') $sql .= " AND id_group = '{$groupId}'";
    $sql .= " ORDER BY id DESC;";
}
else if ($q != "all") $sql="SELECT * FROM `file` WHERE category = '".$q."' AND id_group = {$groupId} ORDER BY id DESC";
else $sql = "SELECT * FROM `file` WHERE id_group = {$groupId} ORDER BY id DESC";                                                       
$result = mysql_query($sql);
if (!isset($_GET['page'])) $strRet = "";
$result = mysql_query($sql);                     
while ($file = mysql_fetch_array($result)) {
    $strRet .= cGroup::showOneFile($file, $userId);          
} 
if (!isset($_GET['page'])) {
    echo $strRet; 
    mysqli_close($connect);
}


?> 