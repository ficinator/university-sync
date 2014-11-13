<?php
function showOneResult($link, $name, $type, $content, $group) {
    $strRet = "";
    $strRet .= "<li class=\"mainSearchResult\" onclick=\"window.location='{$link}'\">";
    $strRet .= "<img src=\"./images/";
    if ($type == 'note') $strRet .= "note.jpg\">";
    else if ($type == 'article') $strRet .= "article.jpg\">";
    else if ($type == 'group') $strRet .= "ic_group_blk_120px.png\">";
    $strRet .= "<div id=\"mainSearchResultContent\">";
    $strRet .= "<div id=\"mainSearchResultName\" class=\"";
    if ($type == 'note') $strRet .= "mainSearchNote";
    else if ($type == 'article') $strRet .= "mainSearchArticle";
    else if ($type == 'group') $strRet .= "mainSearchGroup";
    $strRet .= "\">";
    $strRet .= $name;
    $strRet .= "</div>";    // mainSearchResultName
    $strRet .= "<span>{$group['name']}</span><br>";
    if ($type == 'note') $strRet .= $content;
    $strRet .= "</div>";    // mainSearchResultContent
    $strRet .= "</li>";
    return $strRet;
}                                   

$strRet .= "<ul>";
// Skupiny
$sql = "SELECT * FROM `group` ORDER BY name";
$result = mysql_query($sql);
while($group = mysql_fetch_array($result)) {
    $group['name'] = strtolower($group['name']);
    $group['name'] = strtr($group['name'], $table);
    $link = "?page=group&id={$group['id']}";
    foreach($s_arr as $word) {
        if (strstr($group['name'], $word)) {  
            $strRet .= showOneResult($link, $group['name'], "group", null, null);
            break;
        } 
    }          
}
// Poznamky a Clanky
$sql = "SELECT * FROM `note` ORDER BY id DESC";
$result = mysql_query($sql);
while ($note = mysql_fetch_array($result)) {                          // vyhladavanie medzi NOTES
    if ($note['visual'] == '1') $type = "article";
    else $type = "note";
    $found = false;
    $tmp = file_get_contents($note['path']);
    $json = json_decode($tmp, true);
    if ($json != null) {
        $groupId = $json['GroupId'];
        $group = mysql_fetch_array(mysql_query("SELECT * FROM `group` WHERE id = {$groupId}"));
        $link = "?page=group&id={$note['id_group']}";
        if ($note['id_folder'] != '0') {
            $folderId = $note['id_folder'];
            $link .= "&show=folder&idFolder={$folderId}&showNote={$note['id']}";
        }
        else {
            $link .= "&show=notes&showNote={$note['id']}";
            $folderId = null;
        }
        foreach ($json['KeyWords'] as $keyword) {     // podla keywords
            $keyword = strtolower($keyword);
            $keyword = strtr($keyword, $table);
            $kw_arr = explode(" ", $keyword);    // rozdeli na slova po medzerach 
            foreach($kw_arr as $kw) {
                if (strstr($kw, $s)) {    
                    $strRet .= showOneResult($link, $json['KeyWords'][0], $type, $json['Content'], $group);
                    $found = true;
                    break 2;
                } 
            }
        } 
        if ($found != true) {                                               // podla content
            $content = strtolower($json['Content']);
            $content = strtr($content, $table);
            foreach($s_arr as $word) {
                if (strstr($content, $word)) {  
                    $strRet .= showOneResult($link, $json['KeyWords'][0], $type, $json['Content'], $group);
                    $found = true;
                    break 2;
                }
            }         
        }
        if ($found != true) {                                               // podla referencii
            foreach ($json['References'] as $ref) {
                $ref = strtolower($ref);
                $ref = strtr($ref, $table);
                foreach($s_arr as $word) {
                    if (strstr($ref, $word)) {  
                        $strRet .= showOneResult($link, $json['KeyWords'][0], $type, $json['Content'], $group);
                        $found = true;
                    }
                }
            }          
        }
    }        // $json != null
}           // while
$strRet .= "</ul>";
?>