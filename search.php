<?php
require 'connect.php';
include 'stdlib.php'; 
function getChildrenCategories($id) { 
    $result = mysql_query("SELECT * FROM `category` WHERE id_parent = '{$id}'");
    $sql = '';
    while($category = mysql_fetch_array($result)){
        $sql .= " OR category = '{$category['name']}'"; 
        $sql .= getChildrenCategories($category['id']);
    }
    return $sql;
}

if (isset($_GET['groupId']) && isset($_GET['s'])) {

    $groupId = $_GET['groupId'];  // groupId
    $userId = $_GET['userId'];    // userId
    $show = $_GET['show'];        // show
    $s = $_GET['s'];              // search
    $s = strtolower($s);          // zmeni na male pismenka
    $s = strtr($s, $table);       // zmeni na bez diakritiky
    $s_arr = explode(" ", $s);    // rozdeli na slova po medzerach
                                                        
    if ($groupId == 'all') {
        include "mainSearch.php";
    }
    // NOTES
    if (($show == 'notes' || $show == 'search' || ($show == 'folder' && $_GET['article'] == 'true')) && $_GET['chNotes'] != "false") {// vyhladavanie medzi NOTES
        if ($show == 'notes') $sql = "SELECT * FROM `note` WHERE id_group = {$groupId} AND visual = '0' ORDER BY id DESC";
        else if ($show == 'folder' && $_GET['article'] == 'true') {
            $sql = "SELECT * FROM `note` WHERE id_group = {$groupId} AND visual = '1'";
            if (isset($_GET['category']) && $_GET['category'] != 'all') {
                $sql .= " AND (category = '{$_GET['category']}'";
                $category = mysql_fetch_array(mysql_query("SELECT * FROM `category` WHERE name = '{$_GET['category']}' AND id_group = '{$groupId}'"));
                $sql .= getChildrenCategories($category['id']);
               
                $sql .= ")";
            }
            $sql .= " ORDER BY id DESC";
        }
        else if ($groupId == 'all') $sql = "SELECT * FROM `note` ORDER BY id DESC";
        else $sql = "SELECT * FROM `note` WHERE id_group = {$groupId} ORDER BY id DESC";
        if ($show == 'search' && $s != "") {
            $strRet .= "<div id=\"searchResultsNotes\">";
            $strRet .= "<h3>Poznámky & Články</h3>";
        }

        $result = mysql_query($sql);
        while ($note = mysql_fetch_array($result)) {                          
            $found = false;
            $tmp = file_get_contents($note['path']);
            $json = json_decode($tmp, true);
            if ($json != null) {
                if ($note['id_folder'] != '0') $folderId = $note['id_folder'];
                else $folderId = null;
                if ($show != 'search' && $s == "") {
                    $strRet .= cGroup::showThumbNote($json, $json['GroupId'], $folderId);
                } else {
                    foreach ($json['KeyWords'] as $keyword) {     // podla keywords
                        $keyword = strtolower($keyword);
                        $keyword = strtr($keyword, $table);
                        foreach($s_arr as $word) {
                            $pos = strpos($keyword,$word);
                            if ($pos === 0) {
                                $strRet .= cGroup::showThumbNote($json, $json['GroupId'], $folderId);
                                $found = true;
                                break 2;
                            }
                        }
                    } 
                    if ($found != true) {                                               // podla content
                        $content = strtolower($json['Content']);  
                        $content = strtr($content, $table);
                        foreach($s_arr as $word) {
                            $pos = strpos($content,$word);
                            if (strstr($content, $word)) {  
                                $strRet .= cGroup::showThumbNote($json, $json['GroupId'], $folderId);
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
                                $pos = strpos($ref,$word);
                                if ($pos === 0) {  
                                    $strRet .= cGroup::showThumbNote($json, $json['GroupId'], $folderId);
                                    $found = true;
                                }
                            }
                        }          
                    }
                }     // $s != null
            }        // $json != null
        }           // while
        if ($show == 'search' && $s != "") $strRet .= "</div>";
    }
    
    // NEWS
    if (($show == 'search' || $show == 'news' || $show == undefined) && $_GET['chNews'] != "false") {  
        $sql = "SELECT * FROM `news` WHERE id_group = {$groupId}"; 
        if (isset($_GET['category']) && $_GET['category'] != 'all') {
            $sql .= " AND (category = '{$_GET['category']}'";
            if ($_GET['category'] != 'nezaradené') {
                $sqlTmp = "SELECT * FROM `category` WHERE name = '{$_GET['category']}' AND id_group = '{$groupId}'";
                $parentCat = mysql_fetch_array(mysql_query($sqlTmp));
                $sqlTmp = "SELECT * FROM `category` WHERE id_parent = '{$parentCat['id']}'";
                $resultTmp = mysql_query($sqlTmp);
                while ($childCat = mysql_fetch_array($resultTmp)) {
                    $sql .= " OR category = '{$childCat['name']}'";
                }
            }
            $sql .= ")";
        }
        $sql .= " ORDER BY id DESC";      
        $result = mysql_query($sql);
        if ($show == 'search' && $s != "") {
            $strRet .= "<div id=\"searchResultsNews\">";
            $strRet .= "<h3>Novinky</h3>";
        }
        while ($new = mysql_fetch_array($result)) {
            if ($show != 'search' && $s == "") {
                $strRet .= cGroup::showOneNew($new, $groupId, $userId);
            } else {
                $content = strtolower($new['content']);
                $content = strtr($content, $table);
                foreach($s_arr as $word) {
                    if (strstr($content, $word)) {
                        $strRet .= cGroup::showOneNew($new, $groupId, $userId);
                        break;
                    }
                }
            }
        }
        if ($show == 'search' && $s != "") $strRet .= "</div>";
    }
    
    // SUBORY
    if (($show == 'files' || $show == 'search') && $_GET['chFiles'] != "false") {
        $sql = "SELECT * FROM `file` WHERE id_group = {$groupId}";    // FILES
        if (isset($_GET['category']) && $_GET['category'] != 'all') $sql .= " AND category = '{$_GET['category']}'";
        $sql .= " ORDER BY id DESC";
        $result = mysql_query($sql);
        if ($show == 'search' && $s != "") {
            $strRet .= "<div id=\"searchResultsFiles\">";
            $strRet .= "<h3>Súbory</h3>";        
        }
        while ($file = mysql_fetch_array($result)) {
            if ($show != 'search' && $s == "") {
                $strRet .= cGroup::showOneFile($file, $userId);
            } else {
                $filename = substr(strrchr($file['path'], "/"), 1);
                $filename = strtolower($filename);
                $filename = strtr($filename, $table);
                $info = strtolower($file['info']);
                $info = strtr($info, $table);
                foreach($s_arr as $word) { 
                    if (strstr($info, $word) || strstr($filename, $word)) {
                        $strRet .= cGroup::showOneFile($file, $userId);
                        break 2;
                    }
                }
            }
        }
        if ($show == 'search' && $s != "") $strRet .= "</div>";
    }
    
    // FORUM
    if (($show == 'forum' || $show == 'search') && $_GET['chForum'] != "false") { 
        $group = mysql_fetch_array(mysql_query("SELECT * FROM `group` WHERE id = {$groupId}"));             
        $sql = "SELECT * FROM `topic` WHERE id_group = {$groupId}";
        if (isset($_GET['category']) && $_GET['category'] != 'all') $sql .= " AND category = '{$_GET['category']}'";
        $sql .= " ORDER BY id DESC";    
        $result = mysql_query($sql);
        if ($show == 'search' && $s != "") {
            $strRet .= "<div id=\"searchResultsForum\">";
            $strRet .= "<h3>Fórum</h3>";
        }
        $strRet .= "<table class=\"table table-striped\">";
                    $strRet .= "<tr class=\"table table-striped\">";
                    $strRet .= "<th class=\"tableSubject\">Predmet</th>"
                            .  "<th>Autor</th>"
                            .  "<th>Zobrazenia</th>"
                            .  "<th>Odpovede</th>"
                            .  "<th>Datum</th>";
                    $strRet .= "</tr>";
        while ($topic = mysql_fetch_array($result)) {
            if ($show != 'search' && $s == "") {
                $strRet .= cGroup::getForumTableRow($topic, $group);
            } else {
                $topic['subject'] = strtolower($topic['subject']);
                $topic['subject'] = strtr($topic['subject'], $table);
                foreach($s_arr as $word) {
                    if (strstr($topic['subject'], $word)) {
                        $strRet .= cGroup::getForumTableRow($topic, $group);
                        break 2;
                    }
                }
            }
        }
        $strRet .= "</table>";
        if ($show == 'search' && $s != "") $strRet .= "</div>";
    }
    // vyhladavanie medzi FOLDERS
    if ($show == 'folder' && $_GET['article'] != "true") {     // vyhladavanie medzi FOLDERS
        $path = "./groups/{$groupId}/";
        $sqlFolders = "SELECT * FROM `folder` WHERE id_group = {$groupId}";
        $res = mysql_query($sqlFolders);
        $files = scandir($path);
        $files = array_reverse($files);
        while ($folder = mysql_fetch_array($res)) {
                $sqlUser = "SELECT * FROM `user` WHERE id = {$folder['id_user']}";
                $user = mysql_fetch_array(mysql_query($sqlUser));
                if ($folder) {
                    if ($s == "") $strRet .= cGroup::showThumbFolder($folder, $user);
                    $name = strtolower($folder['name']);
                    $name = strtr($name, $table);
                    $info = strtolower($folder['info']);
                    $info = strtr($info, $table);
                    foreach($s_arr as $word) { 
                        if (strstr($info, $word) || strstr($name, $word) ) {
                            $strRet .= cGroup::showThumbFolder($folder, $user);
                            break 2;
                        }
                    }    
                }
        }
    }  
    echo $strRet;   
} else {
    exit(1);
}
?>