<?php
  $uniSyncUserId = 22;      /// NASTAVIT PODLA POTREBY !
  require "connect.php";
  $strRet = "";
  $groupId = $_GET['groupId'];
  $sql = "SELECT * "
  ."FROM ( "
  ."    SELECT id, date, id_group, id_user, t_name "
  ."    FROM `news` "
  ."    UNION ALL " 
  ."    SELECT id, date, id_group, id_user, t_name "
  ."    FROM `topic` "
  ."    UNION ALL "
  ."    SELECT id, date, id_group, id_user, t_name FROM `file`"
  ."    UNION ALL "
  ."    SELECT id, date, id_group, id_user, t_name FROM `note`"
  ."    UNION ALL "
  ."    SELECT id_topic, date, id_group, id_user, t_name FROM `reply`"
  ."    UNION ALL "
  ."    SELECT id_news, date, id_group, id_user, t_name FROM `comment`"
  ."    UNION ALL "
  ."    SELECT id, date, id_group, id_user, t_name FROM `folder`"
  ."    UNION ALL "
  ."    SELECT id, date, id_group, id_user, t_name FROM `category`"
  .")s "
  ."WHERE id_group =  {$groupId} "
  ."ORDER BY date DESC";
  $result = mysql_query($sql);
  $timezone = date_default_timezone_get();
  date_default_timezone_set($timezone);
  $curDate = date('m/d/Y h:i:s a', time());
  if (mysql_num_rows($result) == 0) $strRet .= "V tejto skupine zatiaľ nie sú žiadne novinky.";
            else {
                $counter = 0;
                while($new = mysql_fetch_array($result)) {
                    if ($counter == 7) break;
                    if ($new['id_user'] != 0) $userId = $new['id_user'];
                    else $userId = $uniSyncUserId;
                    $author = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE id = {$userId}"));
                    $phpdate = strtotime( $new['date'] );
                    $date = date( 'd. m. Y', $phpdate );
                    $time = date( 'G:i', $phpdate ); 
                    if ($new['t_name'] == 'news') {
                        $strRet .= "<div id=\"novinkaThumb\" onclick=\"window.location='?page=group&id={$groupId}&show=news&newsId={$new['id']}'\">";
                        $strRet .= "<div id=\"novinkaThumbContent\">";
                        $strRet .= "<span>{$date}<br />{$time}</span>";
                        $strRet .= "<img src=\"./images/news.png\">";
                        $strRet .= "<div>";
                        $strRet .= "<p>{$author['name']} {$author['surname']}</p> pridal ";
                        $strRet .= "novinku</div>";
                    }
                    else if ($new['t_name'] == 'file') {
                        $strRet .= "<div id=\"novinkaThumb\" onclick=\"window.location='?page=group&id={$groupId}&show=files'\">";
                        $strRet .= "<div id=\"novinkaThumbContent\"><img src=\"./images/file.png\">";
                        $strRet .= "<span>{$date}<br />{$time}</span>";
                        $strRet .= "<div>";
                        $strRet .= "<p>{$author['name']} {$author['surname']}</p> pridal ";
                        $strRet .= "súbor</div>";
                    }
                    else if ($new['t_name'] == 'topic') {
                        $strRet .= "<div id=\"novinkaThumb\" onclick=\"window.location='?page=group&id={$groupId}&show=forum&topic={$new['id']}'\">";
                        $strRet .= "<div id=\"novinkaThumbContent\"><img src=\"./images/forum.gif\">";
                        $strRet .= "<span>{$date}<br />{$time}</span>";
                        $strRet .= "<div>";
                        $strRet .= "<p>{$author['name']} {$author['surname']}</p> pridal ";
                        $strRet .= "tému vo fóre</div>";
                    }
                    else if ($new['t_name'] == 'note') {
                        $strRet .= "<div id=\"novinkaThumb\" onclick=\"window.location='?page=group&id={$groupId}&showNote={$new['id']}";
                        $tmp = mysql_fetch_array(mysql_query("SELECT visual, id_folder FROM `note` WHERE id = {$new['id']}"));
                        if ($tmp['visual'] == '1') $strRet .= "&show=folder&idFolder={$tmp['id_folder']}";
                        else $strRet .= "&show=notes";
                        $strRet .= "'\">";
                        $strRet .= "<div id=\"novinkaThumbContent\">";
                        $strRet .= "<span>{$date}<br />{$time}</span>";
                        if ($tmp['visual'] == '1') $strRet .= "<img src=\"./images/article.jpg\">";
                        else $strRet .= "<img src=\"./images/note.jpg\">";
                        $strRet .= "<div>";
                        $strRet .= "<p>{$author['name']} {$author['surname']}</p> pridal ";
                        if ($tmp['visual'] == '1') $strRet .= "poznámku</div>";
                        else {
                            $strRet .= "poznámku</div>";
                        }
                    }
                    else if ($new['t_name'] == 'fold') {
                        $strRet .= "<div id=\"novinkaThumb\" onclick=\"window.location='?page=group&id={$groupId}&show=folder&idFolder={$new['id']}'\">";
                        $strRet .= "<div id=\"novinkaThumbContent\"><img src=\"./images/folder.jpg\">";
                        $strRet .= "<span>{$date}<br />{$time}</span>";
                        $strRet .= "<div>";
                        $strRet .= "<p>{$author['name']} {$author['surname']}</p> pridal ";
                        $strRet .= "novú zložku</div>";
                    }
                    else if ($new['t_name'] == 'catg') {
                        $strRet .= "<div id=\"novinkaThumb\">";
                        $strRet .= "<div id=\"novinkaThumbContent\"><img src=\"./images/category.png\">";
                        $strRet .= "<span>{$date}<br />{$time}</span>";
                        $strRet .= "<div>";
                        $strRet .= "<p>{$author['name']} {$author['surname']}</p> pridal ";
                        $strRet .= "novú kategóriu</div>";
                    }
                    else if ($new['t_name'] == 'comm') {
                        $strRet .= "<div id=\"novinkaThumb\" onclick=\"window.location='?page=group&id={$groupId}&show=news&newsId={$new['id']}'\">";
                        $strRet .= "<div id=\"novinkaThumbContent\"><img src=\"./images/comment.png\">";
                        $strRet .= "<span>{$date}<br />{$time}</span>";
                        $strRet .= "<div>";
                        $strRet .= "<p>{$author['name']} {$author['surname']}</p> pridal ";
                        $strRet .= "komentár k novinke</div>";
                    }
                    else if ($new['t_name'] == 'reply') {
                        $strRet .= "<div id=\"novinkaThumb\" onclick=\"window.location='?page=group&id={$groupId}&show=forum&topic={$new['id']}'\">";
                        $strRet .= "<div id=\"novinkaThumbContent\"><img src=\"./images/forum.gif\">";
                        $strRet .= "<span>{$date}<br />{$time}</span>";
                        $strRet .= "<div>";
                        $strRet .= "<p>{$author['name']} {$author['surname']}</p> ";
                        $strRet .= "odpovedal vo fóre</div>";
                    }
                    $strRet .= "</div>";   // novinkaThumbContent
                    
                    $strRet .= "</div>";  // novinkaThumb
                    $counter++;
                }
            }
    echo $strRet;
?>