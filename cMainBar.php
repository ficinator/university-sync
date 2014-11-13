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
            $strRet .= "<a class=\"title\" href=\"?page=home\">
                            <div id=\"titleBar\">
                                <img src=\"./images/logo_154x60px\" class=\"titleLogo\">
                            </div>";        // titleBar
            $strRet .= "</a>";
            $strRet .= "<div id=\"mainSearch\" >";
            $strRet .= "<input  placeholder=\"rýchle vyhľadávanie\" type=\"text\" name=\"searchBox\" class=\"mainSearchBox\">";
            $strRet .= "<input type=\"hidden\" value=\"{$this->user['id']}\" id=\"userId\"/>"; // premenna do Javascriptu
            $strRet .= "<div id=\"mainResults\">";                                                                        
            $strRet .= "</div>";    //mainResults
            $strRet .= "</div>";    //mainSearch      
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
            $strRet .= "<div id=\"loginBar\">";
            if ($_GET['page'] == 'home' && !isset($_GET['id'])) $strRet .= "<div class=\"arrow-right arrow-login\"></div>";
            $strRet .= "<a class=\"title\" href=\"?page=home\">";          
            $filename = "./users/{$this->user['id']}/userPhoto.jpg";
            if (file_exists($filename)) $path = $filename;
            else $path = "./images/ic_avatar_blk_32px.png"; 
            $strRet .= "<img src=\"{$path}\" id=\"userPhotoImg\" class=\"thumbPhotoUser\">{$this->user['name']} {$this->user['surname']}";   
            $strRet .= "</a>"; 
            $strRet .= "<a class=\"btn btn-yellow right black logout\" title=\"odhlásiť sa\" href=\"?page=action&type=logout\">Log Out</a>";
            $strRet .= "</div>"; // loginBar   
            $strRet .= "</div>";    //mainBar
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
