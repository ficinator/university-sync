<?php
require 'connect.php'; 
if (isset($_GET['groupId'])) {
    $strRet = "";
    $sql = "SELECT * FROM `topic` WHERE id_user = {$_GET['userId']}";
    if ($_GET['groupId'] != "all") $sql .= " AND id_group = {$_GET['groupId']}";
    $sql .= " ORDER BY date DESC";
    $result = mysql_query($sql);
}
if ($_GET['page'] == 'group') {
    $tmp = "Autor";
} else {
    $tmp = "Skupina";
}
$strRet .= "<table class=\"table table-striped\">";
$strRet .= "<tr class=\"table table-striped\">";
$strRet .= "<th class=\"tableSubject\">Predmet</th>"
        .  "<th>{$tmp}</th>"
        .  "<th>Zobrazenia</th>"
        .  "<th>Odpovede</th>"
        .  "<th>Datum</th>";
$strRet .= "</tr>";
while ($topic = mysql_fetch_array($result)) {         
    $sqlReply = "SELECT * FROM `reply` WHERE id_topic = {$topic['id']}";
    $answers = mysql_num_rows(mysql_query($sqlReply)) - 1;
    
    if ($_GET['page'] == 'group') {
        $sqlUser = "SELECT * FROM `user` WHERE id = {$topic['id_user']}";
        $user = mysql_fetch_array(mysql_query($sqlUser));
        $group = $this->group;        
    } else {
        $sqlGroup = "SELECT * FROM `group` WHERE id = {$topic['id_group']}";
        $group = mysql_fetch_array(mysql_query($sqlGroup));
    }
    $strRet .= "<tr class=\"table table-striped\" href=\"index.php?page=group&id={$group['id']}&show=forum&topic={$topic['id']}\">";
    $strRet .= "<td class=\"table tableSubject table-striped\">";
    $strRet .= "<a href=\"index.php?page=group&id={$group['id']}&show=forum&topic={$topic['id']}\">";
    $strRet .= "<div>{$topic['subject']}</div>";
    $strRet .= "</a></td>";
    if ($_GET['page'] == 'home') $strRet .= "<td>{$group['name']}</td>";
    else $strRet .= "<td>{$user['name']} {$user['surname']}</td>";
    $strRet .= "<td>{$topic['views']}</td>"
            .  "<td>{$answers}</td>"
            .  "<td>{$topic['date']}</td>";
    $strRet .= "</tr>";
}
$strRet .= "</table>";
if (isset($_GET['groupId'])) {
    echo $strRet;
}
mysqli_close($connect);
?>