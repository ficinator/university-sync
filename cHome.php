<?php
class cHome extends cPage {
        protected $notes;
        protected $notesVisual;
        protected $forumReplies;
        protected $files;
    public function __construct() {
        parent::__construct();
        if (isset($_GET['userId']) && $_GET['userId'] != $this->user['id']) {    
            $sql = "SELECT * FROM `user` WHERE id = {$_GET['userId']}";
            $result = mysql_query($sql);
            $user = mysql_fetch_array ($result);
        }
        else $user = $this->user; 
        $this->notes = mysql_num_rows(mysql_query("SELECT * FROM `note` WHERE id_user = {$user['id']} AND visual = 0"));
        $this->notesVisual = mysql_num_rows(mysql_query("SELECT * FROM `note` WHERE id_user = {$user['id']} AND visual = 1"));
        $this->forumReplies = mysql_num_rows(mysql_query("SELECT * FROM `reply` WHERE id_user = {$user['id']}"));
        $this->files = mysql_num_rows(mysql_query("SELECT * FROM `file` WHERE id_user = {$user['id']}"));    
    }

    public function notLogged() {
        $strRet = "";
        $strRet .= ""
                . "<div id=\"opening\">";
                // . " <div id=\"help\" onclick=\"window.location='?page=help'\">"       
                // . " <a href=\"?page=help\"></a>"
                // . " </div>" //help
        $strRet .= " <div id=\"login\">";
        $strRet .= " <form action=\"?page=action&type=login\" method=\"post\">"
                . " <input type=\"text\" name=\"email\" required placeholder=\"E-mail\">"
                . " <input type=\"password\" name=\"password\" required placeholder=\"Heslo\" >"
                . " <input type=\"submit\" class=\"btn btn-default\" value=\"Prihlásiť\">"
                . " </form>"
                . " </div>" // login
                . " <div id=\"regBar\">"
                . " <a href=\"?page=register\">"
                . " <input type=\"button\" class=\"btn btn-default\" value=\"Registrácia\">"
                . " </a></div>" // regBar
                . "</div>"; //opening
        // $strRet .= '<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
        //             </fb:login-button>

        //             <div id="status">
        //             </div>';
        return $strRet; 
    }
    
    public function getAllGroups() {
        $strRet = "";
        $strRet .= "<div id=\"allGroups\" title=\"všetky skupiny\">";
        $strRet .= "<h2>Všetky skupiny</h2>";
        $sql = "SELECT * FROM `group`";
        $result = mysql_query($sql);
        while ($group = mysql_fetch_array($result)) {
            $strRet .= "<a href=\"?page=group&id={$group['id']}\">";
            $strRet .= $group['name'];
            $strRet .= "</a><br>";
        }
        $strRet .= "</div>";
        return $strRet;
    }
    
    public function getUserGroupsFilter() {
        $strRet = "";
        $sql = "SELECT * FROM member WHERE id_user = {$this->user['id']} ORDER BY admin DESC";
        $result = mysql_query($sql);
        $rows = mysql_num_rows($result);
        if ($rows < 2)
            return $strRet;
        $strRet .= "<div id=\"userGroupsFilter\">";
        $strRet .= "<div id=\"filterTitle\">";
        $strRet .= "<h3>Filter</h3>";
        $strRet .= "</div>";   //filterTitle          
        while ($row = mysql_fetch_array($result)) {
            $idGroup = $row['id_group'];
            $sql2 = "SELECT * FROM `group` where id = {$idGroup}";
            $result2 = mysql_query($sql2);
            $group = mysql_fetch_array($result2);
            $link = "?page=home";
            if ($_GET['filterIdGroup'] != $group['id']) {
                if (isset($_GET['show']) && $_GET['show'] != 'settings') $link .= "&show={$_GET['show']}";
                $link .= "&filterIdGroup={$group['id']}";
            } else {
                if (isset($_GET['show'])) $link .= "&show={$_GET['show']}";
            }
            $strRet .= "<a href=\"{$link}\">";
            /*if (isset ($_GET['filterIdGroup']) && $_GET['filterIdGroup'] == $group['id']) {
                $strRet .= "<div class=\"arrow-left\"></div>";
            }   */
            $strRet .= "<div class=\"thumbGroup";
            if (isset ($_GET['filterIdGroup']) && $_GET['filterIdGroup'] == $group['id']) $strRet .= " activeGroupFilter";
            $strRet .= "\">";
            $strRet .= $group['name'];
            if ($row['admin'] == 1) $strRet .= " - A";
            $sql = "SELECT * FROM university WHERE name = '{$group['university']}'";
            $res = mysql_query($sql);
            $uni = mysql_fetch_array($res);
            $strRet .= "<br><span>{$uni['short_name']}</span>";
            $strRet .= "</div>";
            $strRet .= "</a>";
        }
        $strRet .= "</div>"; // userGroupsFilter
        return $strRet;
    }
    
    public function getUserNotes() {                
        $strRet = ""; 
        $sql = "SELECT * FROM `note` WHERE id_user = '{$this->user['id']}' AND visual = '0' ";
        if (isset($_GET['filterIdGroup'])) $sql .= "AND id_group = '{$_GET['filterIdGroup']}';"; 
        $rows = mysql_num_rows(mysql_query($sql));
        $strRet .= "<div id=\"userNotes\"><h2>Moje poznámky<span class=\"cntOfNotes\">({$rows})</span></h2>";
        $strRet .= "<input type=\"hidden\" value=\"{$this->user['id']}\" id=\"userId\"/>"; // premenna do Javascriptu
        $strRet .= "<input type=\"hidden\" value=\"0\" id=\"visual\"/>"; // premenna do Javascriptu
        //$strRet .= $this->getUserFilter("userSelectNotes");
        $strRet .= "<div id=\"allNotes\">";
        include "getUserNotes.php";
        $strRet .= "</div>"; // allNotes
        $strRet .= "</div>"; //userNotes
        return $strRet;
    }
    
    public function getUserVisualNotes() {
        $strRet = "";
        $sql = "SELECT * FROM `note` WHERE id_user = '{$this->user['id']}' AND visual = '1' ";
        if (isset($_GET['filterIdGroup'])) $sql .= "AND id_group = '{$_GET['filterIdGroup']}';";
        $rows = mysql_num_rows(mysql_query($sql)); 
        $strRet .= "<div id=\"userVisualNotes\"><h2>Moje poznámky<span class=\"cntOfNotes\">({$rows})</span></h2>";
        $strRet .= "<input type=\"hidden\" value=\"{$this->user['id']}\" id=\"userId\"/>"; // premenna do Javascriptu
        $strRet .= "<input type=\"hidden\" value=\"1\" id=\"visual\"/>"; // premenna do Javascriptu
        //$strRet .= $this->getUserFilter("userSelectNotes");
        $strRet .= "<div id=\"allNotes\">";
        include "getUserNotes.php";
        $strRet .= "</div>"; // allNotes                                                                          
        $strRet .= "</div>";  //userVisualNotes
        return $strRet;
    }
    
    public function getUserTitle() {
        $strRet = "";
        $strRet .= "<div id=\"userTitle\" title=\"meno užívateľa\">";      
        $strRet .= "<div id=\"curTitle\">";
        if(isset($_GET['userId']) && $_GET['userId'] != $this->user['id']) {  
            $sql = "SELECT * FROM `user` WHERE id = {$_GET['userId']}";
            $result = mysql_query($sql);
            $user = mysql_fetch_array ($result);
            $strRet .= "<h1>{$user['name']} {$user['surname']}</h1>";
            $strRet .= "{$user['university']}&nbsp;";
        }  else {
            $strRet .= "<div id=\"name\"><h1>{$this->user['name']} {$this->user['surname']}</h1></div>";
            $strRet .= '<span class="grey">' . $this->user['email'] . '</span><br>';
            $strRet .= "{$this->user['university']}&nbsp;";
        }    
        $strRet .= "</div>";   // curTitle
        $strRet .= "</div>";   // userTitle
        return $strRet;
    }
    
    public function getUserPhoto() {
        $strRet = "";
        $strRet .= "<div id=\"userPhoto\">";
        if (isset($_GET['userId'])) $userId = $_GET['userId'];
        else $userId = $this->user['id'];
        $filename = "./users/{$this->user['id']}/userPhoto.jpg";
        if (file_exists($filename)) {
            $photoExists = true;
            $path = $filename;
        }
        else {
            $path = "./images/ic_avatar_blk_120px.png"; 
            $photoExists = false;
        }
        $strRet .= "<img src=\"{$path}\" id=\"userPhotoImg\">";
        $strRet .= "</div>";     //userPhoto
        if (!$photoExists) {
            $strRet .= "<div class=\"hidden\">";
            $strRet .= $this->getChangePhoto();
            $strRet .= "</div>";
        }
        return $strRet;
    }
    
    public function getUserRank() {
    	$rank = 0;
    	$likes = 0;
    	if (isset($_GET['userId'])) {    
            $sql = "SELECT * FROM `user` WHERE id = {$_GET['userId']}";
            $result = mysql_query($sql);
            $user = mysql_fetch_array ($result);
        }
        else $user = $this->user;
        $resLikes = mysql_query("SELECT * FROM `likes`");
        while ($like = mysql_fetch_array($resLikes)) {
            $note = mysql_fetch_array(mysql_query("SELECT * FROM `note` WHERE id = {$like['id_note']}"));
            $userLike = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE id = {$note['id_user']}"));
            if ($userLike['id'] == $user['id']) {
                if ($like['is_like'] == 1) {
                	$rank += 3;          // 3
                	$likes++;
                }
                else {
                	$rank += -3;   		 // -3
                	$likes--;
                }
            }
        }
        $strRet = "";
        $strRet .= "<div id=\"userRank\" title=\"štatistické informácie o užívateľovi\">";
        $strRet .= "<div class=\"userRankTitle\"> <h3>Štatistika užívateľa</h3></div>";
        
        $strRet .= "<div id=\"userRankLeft\">"
                .  "<li><label>Počet hodnotení</label>";
        $strRet .= $likes; 
        $strRet .= "</li>";    
        $strRet .= "<li><label>Poznámky</label>";
        $strRet .= $this->notesVisual;
        $strRet .= "</li>";
        $strRet .= "</div>";    
        $strRet .= "<div id=\"userRankRight\">";
        $strRet .= "<li><label>Fórum príspevky</label>";
        $strRet .= $this->forumReplies;
        $strRet .= "</li>";
        $strRet .= "<li><label>Pridané súbory</label>";
        $strRet .= $this->files;
        $strRet .= "</li>";
        $strRet .= "</div>"; 
        $strRet .= "<br>";  
        $strRet .= "<span>"
                .  "KREDIBILITA ";                    
        $strRet .= "<label id=\"rank\" title=\"hodnovernosť užívateľa\">";        
                        
        $rank = $this->notes;                                   // 1  
        $rank += ($this->notesVisual * 1.3);                    // 1.3
        $rank += ($this->forumReplies * 0.1);                          // 0.1
        $rank += ($this->files * 0.4);                          // 0.4                       
        
        $strRet .= $rank/10;
        $strRet .= "</label>";
        $strRet .= "</span>";
        $strRet .= "</div>";
        return $strRet;
    }
    
    public function getUserButtons() {
        $strRet = "";
        $strRet .= "<div id=\"userButtons\">";
        $strRet .= "<div id=\"userMainButtons\">";
        /*$strRet .= "<div class=\"menuBtn";
        $strRet .= (!isset($_GET['show']) ?" active\"" : "\"");
        $strRet .= "title=\"moje poznámky\" onclick=\"window.location='?page=home'\">POZNÁMKY</div>";*/ 
        $strRet .= "<div class=\"menuBtn";
        $strRet .= (!isset($_GET['show']) ?" active\"" : "\"");
        $strRet .= "title=\"moje poznámky\" onclick=\"window.location='?page=home'\">poznámky</div>";
        $strRet .= "<div class=\"menuBtn";
        $strRet .= ($_GET['show'] == 'forum' ?" active\"" : "\"");
        $strRet .= "title=\"moje témy vo fóre\" onclick=\"window.location='?page=home&show=forum'\">TÉMY VO FÓRACH</div>";
        $strRet .= "<div class=\"menuBtn";
        $strRet .= ($_GET['show'] == 'files' ?" active\"" : "\"");
        $strRet .= "title=\"moje súbory\" onclick=\"window.location='?page=home&show=files'\">SÚBORY</div>";
        $strRet .= "<div class=\"menuBtn";
        $strRet .= ($_GET['show'] == 'settings' ?" active\"" : "\"");
        $strRet .= "title=\"moje súbory\" onclick=\"window.location='?page=home&show=settings'\">INFO</div>";        
        $strRet .= "</div>";    //userMainButtons
        /*$strRet .= "<div id=\"userRightButtons\">";
        $strRet .= "<div class=\"groupBtnRight";
        $strRet .= ($_GET['show'] == 'settings' ?" active\"" : "\"");
        $strRet .= "title=\"súbory\" onclick=\"window.location='?page=home&show=settings'\">";
        $strRet .= "<img src=\"./images/settings.png\">";
        $strRet .= "</div>";
        $strRet .= "</div>";    // userRightButtons */
        $strRet .= "</div>";    //userButtons
        return $strRet;
    }
    
    public function getUserInfo() {
        $strRet = "";
        $strRet .= "<div id=\"userInfo\" title=\"základné informácie o užívateľovi\">";
        $strRet .= "<div id=\"curInfo\">";
        $strRet .= "<h3>Niečo o mne</h3>";
        if (isset($_GET['userId'])) {
            $sql = "SELECT * FROM `user` WHERE id = {$_GET['userId']}";
            $result = mysql_query($sql);
            $user = mysql_fetch_array ($result);
            $strRet .= $user['info'];
            $strRet .= "</div>";     // curInfo
        } else {
            $strRet .= $this->user['info'];
            $strRet .= "</div>";   //curInfo
            $strRet .= "<a href=\"?page=home&show=settings\">";
            $strRet .= "zobraziť viac";
            $strRet .= "</a>";   
        }  
        $strRet .= "</div>";   // userInfo
        return $strRet;
    }
    
    public function getUserFiles() {
        $strRet = "";
        $strRet .= "<div id=\"userFiles\">";
        $sql = "SELECT * FROM `file` WHERE id_user = {$this->user['id']} ";
        if (isset($_GET['filterIdGroup'])) $sql .= "AND id_group = '{$_GET['filterIdGroup']}' ";        
        $sql .= "ORDER BY id DESC";
        $cntOfFiles = mysql_num_rows(mysql_query($sql));
        $strRet .= "<h2>Moje súbory<span class=\"cntOfNotes\">({$cntOfFiles})</span></h2>";
        //$strRet .= $this->getUserFilter("userSelectFiles");
        $strRet .= "<div id=\"allFiles\">";  
        include "showFiles.php";
        $strRet .= "</div>";      //allFiles
        $strRet .= "</div>";      // userFiles
        return $strRet;
    }
    
    public function getUserFilter($id) {
        $sqlGroups = "SELECT * FROM `member` WHERE id_user = {$this->user['id']}";
        $resGroups = mysql_query($sqlGroups);
        $strRet = "";
        $strRet .= "<div id=\"filter\">";
        $strRet .= "<input type=\"hidden\" value=\"{$this->user['id']}\" id=\"userId\"/>"; // premenna do Javascriptu
        $strRet .= "Filter:";
        $strRet .= "<select id=\"{$id}\">";      
        $strRet .= "<option value=\"all\">(Všetky)</option>";
        while($groupId = mysql_fetch_array($resGroups)) {
            $sql2 = "SELECT * FROM `group` where id = {$groupId['id_group']}";
            $result2 = mysql_query($sql2);
            $group = mysql_fetch_array($result2);
            $strRet .= "<option value=\"{$group['id']}\">{$group['name']}</option>";
        }
        $strRet .= "</select>";
        $strRet .= "</div>"; // filter 
        return $strRet;
    }
    
    public function getUserForum() {
        $strRet = "";
        $sql = "SELECT * FROM `topic` WHERE id_user = {$this->user['id']} ";
        if (isset($_GET['filterIdGroup'])) $sql .= "AND id_group = '{$_GET['filterIdGroup']}' ";
        $sql .= "ORDER BY date DESC";
        $result = mysql_query($sql);
        $forumTopics = mysql_num_rows($result);
        $strRet .= "<div id=\"forum\"><h2>Moje témy vo fórach<span class=\"cntOfNotes\">({$forumTopics})</span></h2>";
        //$strRet .= $this->getUserFilter("userSelectForum");
        $strRet .= "<div id=\"allForum\">";
        //$strRet .= cGroup::getForumTable($result);
        include "getForum.php";
        $strRet .= "</div>";    // allForum
        $strRet .= "</div>";    // forum
        return $strRet;
    }

    public function getChangePhoto() {
        $strRet = '';
        $strRet .= "<form action=\"?page=action&type=uploadUserPhoto&userId={$this->user['id']}\" method=\"post\" enctype=\"multipart/form-data\">";
        $strRet .= "<input type=\"file\" name=\"photo\">";
        $strRet .= "<button type=\"submit\" class=\"btn btn-success btn-sm\">Nahraj</button>";
        $strRet .= "</form>";
        return $strRet;
    }
    
    public function getUserEditSettings() {
        $strRet = "";
        $strRet .= "<div id=\"editTitle\">";
        $strRet .= "<form action=\"?page=action&type=userEditProfile&userId={$this->user['id']}\" method=\"post\">";
        $strRet .= cGroupAdd::setName($this->user['name']);
        $strRet .= cGroupAdd::setSurname($this->user['surname']);
        $strRet .= cGroupAdd::setUniversity($this->user['university']);         
        $strRet .= "<textarea name=\"info\">{$this->user['info']}</textarea>";
        $strRet .= "<input type=\"submit\" value=\"Uprav\" class=\"btn btn-default btn-editTitle\">";
        $strRet .= "<button class=\"btn transparent\" type=\"button\" onclick=\"window.location='?page=home&show=settings'\">Späť</button>"; 
        $strRet .= "</form>";     
        $strRet .= "<div id=\"changePhoto\">";
        $strRet .= $this->getChangePhoto();
        $strRet .= "</div>";    // changePhoto
        $strRet .= "</div>";  //editTitle
        return $strRet;                                          
    }
    
    public function getUserSettings() {
        $strRet = "";
        $strRet .= "<div id=\"userInfo\">";
        $strRet .= "<span class=\"editUserInfo\"><button class=\"btn btn-default btn-xs transparent\" title=\"upraviť\" onclick=\"window.location='?page=home&show=settings&e=true'\">&#10000</button></span>";
        $strRet .= "<label>Meno:</label> {$this->user['name']}<br />";
        $strRet .= "<label>Priezvisko:</label> {$this->user['surname']}<br />";
        $strRet .= "<label>Univerzita:</label> {$this->user['university']}<br />";
        $strRet .= "<label>E-mail:</label> {$this->user['email']}<br />";
        $strRet .= "<h3>Niečo o mne</h3>";
        $strRet .= "{$this->user['info']}";
        $strRet .= "</div>";    //userInfo    
        
        $strRet .= "<div id=\"changePassword\">";
        $strRet .= "Zmena hesla";
        $strRet .= "<form action=\"?page=action&type=changePassword&userId={$this->user['id']}\" method=\"post\">"; 
        $strRet .= "Povodne <input type=\"password\" required name=\"oldPwd\"><br />";
        $strRet .= "Nove <input type=\"password\" required name=\"pwd1\" id=\"txtNewPassword\" class=\"txtConfirmPassword\"><br />";
        $strRet .= "Nove znova <input type=\"password\" required name=\"pwd2\" id=\"txtConfirmPassword\" class=\"txtConfirmPassword\"><br />";
        $strRet .= "<div id=\"checkPasswordMatch\"></div>";
        $strRet .= "<input type=\"submit\" value=\"zmeň\">";                
        $strRet .= "</form>";
        $strRet .= "</div>"; // changePassword  
        return $strRet;
    }

    public function getSearch() {
        $strRet = '';
        $strRet .= "<input  placeholder=\"vyhľadávanie\" type=\"text\" name=\"searchBox\" class=\"mainSearchBox\">";
        $strRet .= "<input type=\"hidden\" value=\"{$this->user['id']}\" id=\"userId\"/>"; // premenna do Javascriptu
        $strRet .= '<button type="button"><img src="./images/search_black_20px.png"></button>';
        $strRet .= "<div id=\"mainResultsSearch\">";                                                                        
        $strRet .= "</div>";    //mainResults;
        return $strRet;
    }
    
    public function getUserContent() {
        $strRet = "";
        if (isset ($_GET['show']) && $_GET['show'] == 'folder') $strRet .= $this->getUserVisualNotes();
        else if (isset ($_GET['show']) && $_GET['show'] == 'forum') $strRet .= $this->getUserForum();
        else if (isset ($_GET['show']) && $_GET['show'] == 'files') $strRet .= $this->getUserFiles();
        else if (isset ($_GET['show']) && $_GET['show'] == 'search') $strRet .= $this->getSearch();
        else if (isset ($_GET['show']) && $_GET['show'] == 'settings') {
            if (isset($_GET['e']) && $_GET['e'] == 'true') {           // edit profile
                $strRet .= $this->getUserEditSettings();
            }
            else $strRet .= $this->getUserSettings();
        }
        else $strRet .= $this->getUserVisualNotes();
        return $strRet;
    }
    
    public function getStalk() {
        $strRet = "";
        $strRet .= "Prisiel si stalkovat cudzi profil?";
        return $strRet;
    }

    public function getUserSidePanel() {
        $strRet .= "";
        $strRet .= "<div id=\"userSidePanel\">";
        $strRet .= "<div id=\"userSidePanelInfo\">";
        $strRet .= "<div id=\"PhotoTitle\">";
        $strRet .= $this->getUserPhoto();
        $strRet .= $this->getUserTitle();
        // $strRet .= "<a href=\"?page=home&show=settings\">info</a>";
        $strRet .= "</div>";    // PhotoTitle
        $strRet .= "</div>"; //userSidePanelInfo
        $strRet .= $this->getUserGroupsFilter();
        $strRet .= $this->getUserRank();
        $strRet .= "</div>";  //userSidePanel
        return $strRet;
    }
    
    public function profile() {
        $strRet = "";
        if ($_SESSION['action_ok'] == true) {
                    unset($_SESSION['action_ok']);
                    $strRet .= "<div id=\"actionOk\">";
                    $strRet .= "akcia úspešne vykonaná";
                    $strRet .= "</div>";
        }
        if (isset($_SESSION['registerUserOk']) && $_SESSION['registerUserOk'] == true) {
            $strRet .= "<div id=\"actionOk\">";
            $strRet .= "Úspešne zaregistrovaný. Vitajte na UniSync!";
            $strRet .= "</div>";
            unset($_SESSION['registerUserOk']);
        }
        $strRet .= $this->getUserSidePanel();
        $strRet .= "<div id=\"userProfile\">";
        if (isset ($_GET['userId']) && $_GET['userId'] != $this->user['id']) {
            $strRet .= $this->getStalk();
            $strRet .= "</div>";   // userProfile
        } else {
            $strRet .= $this->getUserButtons();
            $strRet .= "<div id=\"userContent\">";
            $strRet .= $this->getUserContent();
            $strRet .= "</div>";   // userContent
            $strRet .= "</div>";   // end div userProfile
            
        }
        return $strRet;
    }
    
    public function getContent() {
        $strRet = "";
        
        if(isset($_SESSION['login'])) {
            $strRet .= $this->profile();
        } else {                  // neprihlaseny uzivatel
            $strRet .= $this->notLogged();
        }
        return $strRet;
    }
}
