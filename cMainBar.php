<?php
class cMainBar {
    private $user;
    
    public function __construct() {
        $this->user = cPage::getUser();
    }
    
    public function getContent() {
        $strRet = "";
        if (isset ($_SESSION['login'])) {
            $strRet .= "<div id=\"mainBar\" class=\"narrow\">";
            $strRet .= '<div class="main-title">';
            $strRet .= '<div class="toggle-main-bar"></div>';
            $strRet .= "<a class=\"main-logo\" href=\"?page=home\">";
            $strRet .= '<h1>University<strong>Sync</strong></h1>';
            $strRet .= "</a>";
            $strRet .= "</div>";    // .title
            $strRet .= '<div class="control-panel">';
            $strRet .= "<div id=\"myGroups\">";
            $strRet .= "<div id=\"myGroupsTitle\">";
            $strRet .= "MOJE SKUPINY";
            $strRet .= "<a class=\"groupAdd\" href=\"?page=groupAdd\">Vytvoriť skupinu</a>";
            $strRet .= "</div>";        // myGroupsTitle
            $sql = "SELECT * FROM member WHERE id_user = {$this->user['id']} ORDER BY admin DESC";
            $result = mysql_query($sql);
            if (mysql_num_rows($result) != 0) {
                $strRet .= "<div id=\"changeGroup\">";
                while ($member = mysql_fetch_array($result)) {
                    $sql = "SELECT g.id, g.name, g.university, f.short_name AS faculty"
                        . " FROM `group` AS g JOIN faculty AS f ON g.faculty=f.id"
                        . " WHERE g.id = {$member['id_group']}";
                    $group = mysql_fetch_array(mysql_query($sql));
                    $strRet .= "<a href=\"?page=group&id={$group['id']}\" class=\"group-thumb";
                    if (isset($_GET['id']) && $_GET['id'] == $group['id']) $strRet .= " active";
                    $strRet .= "\">";
                    $filename = "./groups/{$group['id']}/groupPhoto.jpg";
                    if (file_exists($filename)) $path = $filename;
                    else $path = "./images/ic_group_white_32px.png";
                    $strRet .= "<img src=\"{$path}\" class=\"thumb\" />";
                    $strRet .= "<div class=\"details\">";
                    $strRet .= "<span class=\"name\">{$group['name']}</span><br/>";
                    $strRet .= "<span class=\"university\">{$group['university']}</span>";
                    $strRet .= "<span class=\"faculty\">{$group['faculty']}</span>";
                    $strRet .= "</div>";            // .details           
                    $strRet .= "</div>";
                    $strRet .= "</a>";              // .group-thumb
                }
                $strRet .= "</div>";    // changeGroup
            }
            $strRet .= "</div>";  // myGroups
            $strRet .= '</div>';    // .control-panel
            $strRet .= "</div>";    //mainBar

            $filename = "./users/{$this->user['id']}/userPhoto.jpg";
            if (file_exists($filename)) {
                $path = $filename;
                $path2 = $filename;
            }
            else {
                $path = "./images/ic_avatar_white_32px.png";
                $path2 = "./images/ic_avatar_white_120px.png";
            }


            $strRet .= '<div id="top-bar" class="wide">';

            $strRet .= '<div id="loginBar">';
            $strRet .= '<a href="?page=home" class="avatar"><img src="' . $path . '" id="userPhotoImg" class="thumbPhotoUser"/></a>';
            $strRet .= '<div id="user-menu">';
            $strRet .= '<a href="?page=home" class="avatar"><img src="' . $path2 . '"></a>';
            $strRet .= '<a href="?page=home" class="details">';
            $strRet .= '<span class="name">'. $this->user['name'] . ' ' . $this->user['surname'] . '</span><br/>';
            $strRet .= '<span class="university">' . $this->user['university'] . '</span><br/>';
            $strRet .= '<span class="faculty">' . $this->user['faculty'] . '</span></a>';  // details
            $strRet .= '<div class="actions">';
            $strRet .= '<a href="?page=action&amp;type=logout" class="logout">Odhlásiť sa</a>';
            $strRet .= '<a href="?page=home&show=settings" class="settings">Nastavenia</a>';
            $strRet .= '</div>';
            $strRet .= '</div>';    // user-menu
            $strRet .= '</div>';    // loginBar

            $strRet .= '<div id="mainSearch"><form>';
            $strRet .= '<input placeholder="rýchle vyhľadávanie" type="text" name="searchBox" class="mainSearchBox">';
            $strRet .= '<input type="hidden" value="'. $this->user['id']. '" id="userId">';
            $strRet .= '<input type="submit" value="Hľadať">';
            $strRet .= '<div class="advanced-search">Rozšírené vyhľadávanie</div>';
            $strRet .= '<div id="mainResults"></div>';
            $strRet .= '</form></div>'; // mainSearch

            $strRet .= '</div>';    // top-bar

            $strRet .= "<div id=\"wrapper\" class=\"wide\">";    
        }
        else {
            $strRet .= '<div id="top-bar" class="not-logged-in">';
            $strRet .= '<h1>University<strong>Sync</strong></h1>';
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
