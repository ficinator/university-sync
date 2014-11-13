<?php
if (isset($_GET['groupId'])) {
    $visual = $_GET['visual'];
    header("Content-Type: text/html; charset=UTF-8");
    require 'connect.php';
    include 'stdlib.php';
    $sql = "SELECT * FROM `note` WHERE id_user = {$_GET['userId']}"; 
    if ($_GET['groupId'] != "all")
        $sql .= " AND id_group={$_GET['groupId']}";
    $sql .= " AND visual = '{$visual}' ORDER BY id DESC";
}
else { 
    if($_GET['show'] == 'folder') $visual = 1;
    else $visual = 1;
    $sql = "SELECT * FROM `note` WHERE id_user = {$this->user['id']} AND visual = '{$visual}' ";
    if (isset($_GET['filterIdGroup'])) $sql .= "AND id_group = '{$_GET['filterIdGroup']}' ";
    $sql .= "ORDER BY date DESC";
}   
$result = mysql_query($sql);
while ($note = mysql_fetch_array($result)) {
    if ($note['visual'] == $visual) {
        $file = file_get_contents($note['path']);
        $json = json_decode($file, true);
        if ($json != null) {
            if ($note['id_folder'] != '0') $folderId = $note['id_folder'];
            else $folderId = null;
            $strRet .= cGroup::showThumbNote($json, $json['GroupId'], $folderId);
        }
    }   
}
if (isset($_GET['groupId'])) {
    echo $strRet; 
    mysqli_close($connect);
}
?>