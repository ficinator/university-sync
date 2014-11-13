<?php
class cMainBar {
    private $user;
    
    public function __construct() {
        $this->user = cPage::getUser();
    }
    
    public function getContent() {
        $strRet = "";
        if (isset ($_SESSION['login'])) {
            $strRet .= "<div id=\"mainBar\">";
            $strRet .= "<a class=\"title\" href=\"?page=home\">";
            $strRet .= '<h1 class="main-title">University Sync</h1>';
            $strRet .= "</a>"; 
            $strRet .= "<div id=\"myGroups\">";
            $strRet .= "<div id=\"myGroupsTitle\">";
            $strRet .= "MOJE SKUPINY";
            $strRet .= "<a class=\"btn btn-yellow right black groupAdd\" href=\"?page=groupAdd\">Vytvoriť skupinu</a>";
            $strRet .= "</div>";        // myGroupsTitle
            $sql = "SELECT * FROM member WHERE id_user = {$this->user['id']} ORDER BY admin DESC";
            $result = mysql_query($sql);
            if (mysql_num_rows($result) != 0) {
                $strRet .= "<div id=\"changeGroup\">";
                while ($member = mysql_fetch_array($result)) {
                    $group = mysql_fetch_array(mysql_query("SELECT * FROM `group` WHERE id = {$member['id_group']}"));
                    $strRet .= "<a href=\"?page=group&id={$group['id']}\">";
                    $strRet .= "<div class=\"changeGroupLine";                
                    if (isset($_GET['id']) && $_GET['id'] == $group['id']) $strRet .= " activeGroup";
                    $strRet .= "\">";
                    $filename = "./groups/{$group['id']}/groupPhoto.jpg";
                    if (file_exists($filename)) $path = $filename;
                    else $path = "./images/groupDefault.png";
                    $strRet .= "<img src=\"{$path}\" class=\"thumbPhoto\">";
                    $strRet .= "<div class=\"groupShortName\">{$group['name']}";
                    $sql = "SELECT * FROM university WHERE name = '{$group['university']}'";
                    $res = mysql_query($sql);
                    $uni = mysql_fetch_array($res);
                    $sql = "SELECT * FROM faculty WHERE name LIKE '%{$group['faculty']}%' AND id_university = '{$uni['id']}'";
                    $res = mysql_query($sql);
                    $faculty = mysql_fetch_array($res);
                    $strRet .= "<br><span>{$uni['short_name']}</span>";
                    $strRet .= "<br><span>{$faculty['name']}";
                    $strRet .= "</div>";            // .groupShortName            
                    $strRet .= "</div>";            // .changeGroupLine
                    $strRet .= "</a>";
                }
                $strRet .= "</div>";
            }
            $strRet .= "</div>";  // myGroups
            $strRet .= "</div>";    //mainBar

            $filename = "./users/{$this->user['id']}/userPhoto.jpg";
            if (file_exists($filename)) $path = $filename;
            else $path = "./images/ic_avatar_white_32px.png";

            $strRet .= '<div id="top-bar">';
            $strRet .= '<div id="loginBar">';
            $strRet .= '<div class="avatar"><img src="' . $path . '" id="userPhotoImg" class="thumbPhotoUser"></div>';
            $strRet .= '<div id="user-menu">';
            $strRet .= '<a class="title" href="?page=home">' . $this->user['name'] . ' ' . $this->user['surname'] . '</a>';
            $strRet .= '<a class="btn btn-yellow right black logout" href="?page=action&amp;type=logout">Odhlásiť sa</a>';
            $strRet .= '</div>';    // user-menu
            $strRet .= '</div>';    // loginNar
            $strRet .= '<div id="mainSearch"><form>';
            $strRet .= '<input placeholder="rýchle vyhľadávanie" type="text" name="searchBox" class="mainSearchBox">';
            $strRet .= '<input type="hidden" value="'. $this->user['id']. '" id="userId">';
            $strRet .= '<input type="submit" value="Hľadať">';
            $strRet .= '<div class="advanced-search">Rozšírené vyhľadávanie</div>';
            $strRet .= '<div id="mainResults"></div>';
            $strRet .= '</form></div>'; // mainSearch
            $strRet .= '</div>';    // top-bar

            $strRet .= "<div id=\"wrapper\">";    
        }
        else {
            $strRet .= '<div id="top-bar" class="not-logged-in">';
            $strRet .= '<h1 class="main-title">University Sync</h1>';
            $strRet .= '</div>';    //top-bar
            $strRet .= '<div id="wrapper" class="not-logged-in">';   
        }
        $strRet .= '<div id="content">';
        return $strRet;
    }
    
    public function render() {
        echo $this->getContent();
    }
}
