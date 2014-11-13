<?php
class cGroup extends cPage {
    protected $group;
    protected $folder;
    protected $memberOfGroup;  // true or false
    protected $adminOfGroup;   // true or false
    protected $sentReq;        // true or false
    
    public function __construct($id) {
        parent::__construct();
        $sql = "SELECT * FROM `group` WHERE id = {$id}";
        $result = mysql_query($sql);
        $this->group = mysql_fetch_array($result);
        
        $sql = "SELECT * FROM `member` "
             . "WHERE id_user = {$this->user['id']} AND id_group = {$this->group['id']}";            
        $result = mysql_query($sql);
        $member = mysql_fetch_array($result);
        if ($member) { // Ak naslo CLEN SKUPINY
            $this->memberOfGroup = true;
            if($member['admin'] == 1) $this->adminOfGroup = true; // admin
            else $this->adminOfGroup = false;
        } else {
            $this->memberOfGroup = false;
        }
        
        $sql = "SELECT * FROM `member_request` "
             . "WHERE id_user = {$this->user['id']} AND id_group = {$this->group['id']}"; 
        $result = mysql_query($sql);
        if (mysql_fetch_array($result)) { // Ak naslo ADMIN SKUPINY
            $this->sentReq = true;
        } else {
            $this->sentReq = false;
        }   
        if (isset ($_GET['idFolder'])) {
            $sql = "SELECT * FROM `folder` WHERE id = {$_GET['idFolder']}";
            $result = mysql_query($sql);
            $this->folder = mysql_fetch_array($result); 
        }
    }                                    
    
    public function getRequest() {
        $strRet = "";
        $sql = "SELECT * FROM `member_request` "
             . "WHERE id_group = {$this->group['id']}";
        $result = mysql_query($sql);
        $cnt = mysql_num_rows($result);                       // pocitadlo ziadosti
        if ($cnt != 0)   {
            $strRet .= "<div id=\"getGroupRequest\">";
            $strRet .= "<button type=\"button\" class=\"btn btn-success btnGetRequests\">{$cnt}</button>";
            $strRet .= "</div>";
        }
        // HIDE
        $strRet .= "<div id=\"groupRequests\">";
        $strRet .= "<h3>Žiadosti o členstvo v skupine</h3>";
        while ($request = mysql_fetch_array($result)) {
            $sql = "SELECT * FROM `user` "
                 . "WHERE id = {$request['id_user']}";
            $result2 = mysql_query($sql);
            $user = mysql_fetch_array($result2);
            $strRet .= "<a href=javascript:conf(\"?page=action&type=acceptRequest&userId={$user['id']}&groupId={$this->group['id']}\")>";
            $strRet .= "{$user['name']} {$user['surname']}";
            $strRet .= "</a><br>";
        }         
        $strRet .= "</div>";
        return $strRet;
    }
    
    public function groupInfo() {
        $strRet = "";
        $strRet .= "<div id=\"groupInfo\">";
        if($_GET['show'] != 'info') {
            $strRet .= "<div id=\"curInfo\">"
                     . "{$this->group['info']}";
            $strRet .= "</div>";   // curInfo
            $strRet .= "<a href=\"?page=group&id={$this->group['id']}&show=settings&p=info\">";
            $strRet .= "zobraziť viac";
            $strRet .= "</a>";
            //if ($this->adminOfGroup) $strRet .= "<span class=\"editInfoBtn\"><button class=\"btn btn-default transparent btn-xs\" title=\"upraviť informácie o skupine\">&#10000</button></span>";
            
            // Hide , az po odkliknuti sa zobrazi 
            /*$strRet .= "<div id=\"editInfo\">";
            $strRet .= "<form action=\"?page=action&type=groupEditInfo&groupId={$this->group['id']}\" method=\"post\">";
            $strRet .= cGroupAdd::setInfoGroup($this->group['info']);
            $strRet .= "<input type=\"submit\" value=\"Uprav\" class=\"btn btn-default\">"
                     . "</form>";
            $strRet .= "<button class=\"exitEditInfo btn-edit transparent\">Späť</button>";
            $strRet .= "</div>" ; 
            // end of hide    */
        }
        $strRet .= "</div>";   // groupInfo
        return $strRet;
    }
    
    public function groupPhoto() {
        $strRet = "";
        $strRet .= "<div id=\"groupPhoto\">";
        $strRet .= "<img src=\"./groups/{$this->group['id']}/groupPhoto.jpg\" id=\"groupPhotoImg\">";
        //if ($this->adminOfGroup) $strRet .= "<span><button class=\"btn btn-default btn-xs editPhotoBtn\" title=\"zmeniť skupinovú fotografiu\">&#10000</button></span>";
        // hide
        /*$strRet .= "<div id=\"editPhoto\">";
        $strRet .= "<form action=\"?page=action&type=uploadGroupPhoto&groupId={$this->group['id']}\" method=\"post\" enctype=\"multipart/form-data\">";
        $strRet .= "<input type=\"file\" name=\"photo\">";
        $strRet .= "<button type=\"submit\" class=\"btn btn-success btn-sm\">Nahraj</button>";
        $strRet .= "</form>";
        $strRet .= "<button class=\"exitEditPhoto btn btn-default btn-sm\">Späť</button>";
        $strRet .= "</div>";  */
        // end of hide
        $strRet .= "</div>";
        return $strRet;
    }
    
    public function groupTitle() {
        $strRet = "";
        $strRet .= "<div id=\"groupTitle\">";
        $strRet .= "<div id=\"curTitle\" onclick=\"window.location='?page=group&id={$this->group['id']}'\">";
        $strRet .= "<div id=\"name\"><h1>".$this->group['name']."</h1></div>";
        $strRet .= "{$this->group['university']}";
        $strRet .= "</div>";
        //if ($this->adminOfGroup) $strRet .= "<span><button class=\"btn btn-default btn-xs transparent editTitleBtn\" title=\"upraviť názov skupiny\">&#10000</button></span>";
        // hide   
        /*$strRet .= "<div id=\"editTitle\">";
        $strRet .= "<form action=\"?page=action&type=groupEditTitle&groupId={$this->group['id']}\" method=\"post\">";
        $strRet .= cGroupAdd::setName($this->group['name']);
        $strRet .= "<br>";  
        $strRet .= "<input type=\"submit\" value=\"Uprav\" class=\"btn btn-default btn-editTitle\">";
        $strRet .= "<button class=\"exitEditTitle btn-edit transparent btn-backTitle\" type=\"button\">Späť</button>";
        $strRet .= cGroupAdd::setUniversity($this->group['university'])       
                 . "</form>";
        $strRet .= "</div>"; 
        // end of hide       */
        $strRet .= "</div>";
        return $strRet;
    }
    
    public function groupMembers() {
        $sql = "SELECT * FROM `member` WHERE id_group = {$this->group['id']} ORDER BY admin DESC";
        $result = mysql_query($sql);
        $count = mysql_num_rows($result);
        $strRet = "";
        $strRet .= "<div id=\"groupUsers\" title=\"členovia skupiny\" onclick=\"window.location='?page=group&id={$this->group['id']}&show=settings&p=members'\">";
        $strRet .= "<h2>Členovia skupiny<span class=\"cntOfNotes\">({$count})</span></h2>";         
        while($member = mysql_fetch_array($result)) {
            $sql = "SELECT * FROM `user` WHERE id = {$member['id_user']}";
            $tmp = mysql_query($sql);
            $user = mysql_fetch_array($tmp);
            if ($this->memberOfGroup || $this->adminOfGroup || $member['admin'] == 1) {   
                $strRet .= "<a href=\"?page=home&userId={$user['id']}\">";
                $strRet .= "<img src=\"./images/";
                if ($member['admin'] ==  1) $strRet .= "avatar_32px.jpg";
                else $strRet .= "avatar_32px.jpg";
                $strRet .= "\" class=\"figure\">".$user['name']." ".$user['surname']."</a>";
                if ($this->adminOfGroup && $this->user['id'] != $user['id']) {
                    $strRet .= "<button type=\"button\" class=\"btn-xs btn btn-default\" onclick=\"conf('?page=action&type=kickMember&memberId={$member['id']}')\">";
                    $strRet .= "k</button>";
                    $strRet .= "<button type=\"button\" class=\"btn-xs btn btn-default\" onclick=\"conf('?page=action&type=makeAdmin&memberId={$member['id']}')\">";
                    $strRet .= "A</button>";
                }
                $strRet .= "<br>";
            }
        } 
        $strRet .= "</div>";
        return $strRet;
    }
    
    public function showThumbNote($noteJson, $groupId, $folderId) {
        $keyword = $noteJson['KeyWords'][0];
        $noteContent = $noteJson['Content'];
        $sql = "SELECT * FROM `note` WHERE id = {$noteJson['Id']}";
        $result = mysql_query($sql);
        $note = mysql_fetch_array($result);
        
        $sqlUser = "SELECT * FROM `user` WHERE id = {$noteJson['UserId']}";
        $user = mysql_fetch_array(mysql_query($sqlUser));
        
        $strRet = ""; 
        $strRet .= "<div id=\"";
        if($note['visual']) {
            $strRet .= "articleThumb";
        } else {
            $strRet .="noteThumb";
        }
        if ($note['visual'] == '1') $strRet .= "\" onclick=\"window.location='?page=group&id={$groupId}&idFolder={$folderId}&show=folder&showNote={$noteJson['Id']}'\"";
        else $strRet .= "\" onclick=\"window.location='?page=group&id={$groupId}&show=notes&showNote={$noteJson['Id']}'\"";
        $strRet .= " class=\"noteThumb\">"; 
        $strRet .= "<h3>{$keyword}</h3>";      
        if ($note['visual']) {} 
        else $strRet .= "<p>".$noteContent."</p>";
        $strRet .= "<span";
        if ($note['likes'] > 0) $strRet .= " class=\"green\">+";
        else if ($note['likes'] < 0) $strRet .= " class=\"red\">";
        else $strRet .= " class=\"grey\">";
        $strRet .= "{$note['likes']}</span>";
        $strRet .= "</div>";
        return $strRet;
    }
    
    
    public function showNote($id) {
        $strRet = "";
        $basic = glob("./groups/*/notes/{$id}.json");
        $visual = glob("./groups/*/*/*/{$id}.json");
        if ($basic) $file = $basic;
        if ($visual) $file = $visual;
        if ($file) {
            foreach($file as $tmp) {
                $file = file_get_contents($tmp);
                $json = json_decode($file, true);
            }
            $idUser = $json['UserId'];
            $sql = "SELECT * FROM `user` WHERE id = {$idUser}";
            $result = mysql_query($sql);
            $user = mysql_fetch_array($result);
            
            $sql = "SELECT * FROM `note` WHERE id = {$id}";
            $result2 = mysql_query($sql);
            $note = mysql_fetch_array($result2);
        }
     
        $strRet .= "<div id=\"showNote\">";
        if (!$file) $strRet .= "Nenašiel sa požadovaný príspevok.";
        else {
            $strRet .= "<div id=\"";
            if ($note['visual'] == '1') $strRet .= "articleContent";
            else $strRet .= "noteContent";
            $strRet .= "\">";
            $title = $json['KeyWords'][0];
            $strRet .= "<h3>{$title}</h3>";
            $strRet .= "<p>".$json['Content']."</p>";
            $strRet .= "</div>";    // content of note/article
            $strRet .= "<div id=\"showNoteInfo\">";
            
            $strRet .= "<div id=\"showNoteKeyword\">";
            $strRet .= "<label>Klúčové slová</label>";
            $strRet .= "<div id=\"showNoteKeywords\">";
            foreach ($json['KeyWords'] as $keyword) {
                $strRet .= "<li>{$keyword}</li>";
            } 
            $strRet .= "</div>"; //showNoteKeywords
            $strRet .= "</div>"; //showNoteKeyword
            // referencie
              
            if ($json['References'][0] != null) {
                $strRet .= "<div id=\"showNoteReference\">";
                $strRet .= "<label>Použité zdroje</label>";          
                $strRet .= "<div id=\"showNoteReferences\">";
                foreach ($json['References'] as $ref) {        
                    $strRet .= "<li>{$ref}</li>";   
                }
                $strRet .= "</div>"; // showNoteReferences
                $strRet .= "</div>"; // showNoteReference
            }
            
             
            // category
            if ($visual) {
                $strRet .= "<div id=\"showNoteCategory\">";
                $strRet .= "<label>Kategória</label>";
                $strRet .= "<div id=\"showNoteCategories\">";
                $strRet .= "<li>{$note['category']}</li>";
                $strRet .= "</div>";
                $strRet .= "</div>";
            }
            
            // images
            if ($visual) {                
                $sql = "SELECT * FROM `image` WHERE id_note = {$id}";
                $res = mysql_query($sql);
                $num = mysql_num_rows($res);
                if ($num != 0) {
                    $strRet .= "<div id=\"showNoteImage\">";
                    $strRet .= "<label>Obrazové prílohy</label>";
                    $strRet .= "<div id=\"showNoteImages\">";                                                 
                    
                    while ($img = mysql_fetch_array($res)) {
                        $strRet .= "<li><a href=\"{$img['path']}\" class=\"fancybox\" rel=\"group\">";     
                        $strRet .= "<img class=\"noteImageThumb\" src=\"{$img['path_thumb']}\">";
                        $strRet .= "</a></li>";
                    }
                    $strRet .= "</div>"; //showNoteImage
                    $strRet .= "</div>"; //showNoteImages
                }       
            }
      
            // likes
            $strRet .= "<div id=\"showNoteRank\">";      
            $strRet .= "<label>Hodnotenie</label>";
            $strRet .= "<div id=\"showNoteRanks\">"; 
            $strRet .= "<li><span";
            if ($note['likes'] > 0) $strRet .= " class=\"green\">+";
            else if ($note['likes'] < 0) $strRet .= " class=\"red\">";
            else $strRet .= " class=\"grey\"> ";
            $strRet .= "{$note['likes']}</span></li>";
            $strRet .= "</div>";
            $strRet .= "</div>";  // showNoteRank
            $strRet .= "</div>";    // showNoteInfo 
            
            $sql = "SELECT * FROM `likes` WHERE id_user = {$this->user['id']} AND id_note = {$id}";
            $result = mysql_query($sql);
            $like = mysql_fetch_array($result);
            if (($this->memberOfGroup || $this->adminOfGroup) && $this->user['id'] != $note['id_user']) {
                $strRet .= "<span class=\"likesButtons\">";
                if($like == null) {
                    $strRet .= "<a href=\"?page=action&type=like&userId={$this->user['id']}&noteId={$id}&groupId={$this->group['id']}\"><button class=\"btn-success likeBtn\">+</button></a>";
                    $strRet .= "<a href=\"?page=action&type=dislike&userId={$this->user['id']}&noteId={$id}&groupId={$this->group['id']}\"><button class=\"btn-danger likeBtn\">-</button></a>";
                } else if ($like['is_like'] == 1) {
                    $strRet .= "<a href=\"?page=action&type=unlike&userId={$this->user['id']}&noteId={$id}&groupId={$this->group['id']}\"><button class=\"btn-success likeBtn\">+</button></a>";
                } else {
                    $strRet .= "<a href=\"?page=action&type=undislike&userId={$this->user['id']}&noteId={$id}&groupId={$this->group['id']}\"><button class=\"btn-danger likeBtn\">-</button></a>";
                }
                $strRet .= "</span>";
            } 
            $strRet .= "<span class=\"noteInfo\">";
            if ($this->user['id'] == $user['id']) {
                $strRet .= "<button class=\"btn btn-default btn-xs\" title=\"vymazať poznámku\" onclick=\"conf('?page=action&type=noteDelete&noteId={$note['id']}')\">X</button>";
                                                                                                      
                $strRet .= "<button class=\"btn btn-default btn-xs\" title=\"upraviť poznámku\" onclick=\"window.location='";
                if (isset($_GET['idFolder'])) $strRet .= "?page=group&id={$this->group['id']}&show=folder&edit=true&type=noteVisualAdd&noteId={$note['id']}&idFolder={$_GET['idFolder']}"; 
                else $strRet .= "?page=group&id={$this->group['id']}&show=notes&edit=true&type=noteAdd&noteId={$note['id']}";
                $strRet .= "'\">&#10000</button><br>";
            }
            $strRet .= $user['name']." ".$user['surname']."<br>";   
            $strRet .= $json['Date'];
            $strRet .= "</span>";
        }    
        $strRet .= "</div>"; // showNote
        return $strRet;
    }
    
    public function showArticlesInFolder() {
        $strRet = "";    
        $strRet .= "<div id=\"folderContent\" title=\"články\">";
        if ($this->folder['id_user'] == $this->user['id']) {
            $strRet .= "<span>";
            $strRet .= "<button class=\"btn btn-default btn-xs\" title=\"vymazať zložku\" onclick=\"conf('index.php?page=action&type=folderDel&groupId={$this->group['id']}&folderId={$this->folder['id']}')\">X</button>";
            $strRet .= "<button class=\"btn btn-default btn-xs\" title=\"upraviť zložku\" onclick=\"window.location='index.php?page=group&id={$this->group['id']}&show=folder&edit=true&type=folderAdd&folderId={$this->folder['id']}'\">&#10000</button><br>";
            $strRet .= "</span>";
        }
        $strRet .= $this->folderHeader();
        if (1 == $this->folder['public'] || $this->folder['id_user'] == $this->user['id']) 
            $strRet .= "<button type=\"button\" class=\"btn btn-primary\" title=\"vytvoriť nový článok\" onclick=\"window.location='{$_SERVER['REQUEST_URI']}&edit=true&type=noteVisualAdd'\">+ Nový článok"
                     . "</button><br /><br /><br />";
                                  
        $sqlArticles = "SELECT * FROM `note` WHERE id_group = {$this->group['id']} AND id_folder = '{$this->folder['id']}' AND visual = 1 ORDER BY date DESC";
        $result = mysql_query($sqlArticles);
        while ($article = mysql_fetch_array($result)) {
            $file = file_get_contents($article['path']);
            $json = json_decode($file, true);       
            $strRet = "OOMG ";
            if ($json != null)      
                $strRet .= $this->showThumbNote($json, $this->group['id'], $this->folder['id']);
        } 
        if ($this->folder['endinfo'] != null) {
            $strRet .= "<div id=\"folderFooter\" title=\"päta článku\" onclick=\"window.location='?page=group&id={$this->group['id']}&show=folder&idFolder={$this->folder['id']}'\">";
            $strRet .= "<p>{$this->folder['endinfo']}</p>";
            $sql = "SELECT * FROM `folder_reference` WHERE id_folder = {$this->folder['id']}";
            $strRet .= "<div id=\"references\">";
            $strRet .= "Použité zdroje: <br>";
            $result = mysql_query($sql);
            while ($ref = mysql_fetch_array($result)) {
                $strRet .= $ref['reference']. "<br>";
            }
            $strRet .= "</div>";          // references
            $strRet .= "</div>";          // folderFooter
        }       
        $strRet .= "</div>";     // folderContent
        return $strRet;
    }      
    
    public function getBasicSearch() {
        $strRet = "";
        $strRet .= "<div id=\"basicSearch\">";
        $strRet .= "<input type=\"hidden\" value=\"{$this->user['id']}\" id=\"userId\"/>"; // premenne do Javascriptu
        $strRet .= "<input type=\"checkbox\" checked class=\"dNone\" id=\"chNotes\"> ";
        $strRet .= "<input type=\"checkbox\" checked class=\"dNone\" id=\"chNews\"> ";
        $strRet .= "<input type=\"checkbox\" checked class=\"dNone\" id=\"chFiles\"> ";
        $strRet .= "<input type=\"checkbox\" checked class=\"dNone\" id=\"chForum\"> ";
        $strRet .= "<input type=\"text\" name=\"search\" placeholder=\"čo chcete vyhľadať?\" class=\"searchBoxInput\">";
        $strRet .= "<button id=\"searchBtn\" class=\"searchBtnYellow\"><img src=\"./images/search_black_20px\"></button>";
        $strRet .= "</div>"; // basicSearch
        return $strRet;
    }
    
    public function showNotes() {
        $strRet = "";
        $strRet .= "<div id=\"groupNotes\">";
        if ($this->memberOfGroup || $this->adminOfGroup) {
            $strRet .= "<button type=\"button\" class=\"btn btn-primary\" title=\"vytvoriť novú poznámku\" onclick=\"window.location='?page=group&edit=true&show=notes&type=noteAdd&id={$this->group['id']}'\">+ Nová poznámka"
                     . "</button>";
        }      
                       
        $path = "./groups/{$this->group['id']}/notes/";
        $files = glob($path.'*.json');
        $files = array_reverse($files);
        $strRet .= "<div id=\"results\" class=\"resultsNotes\">";
        foreach ($files as &$note) {         
            $json = json_decode(file_get_contents($note),true);
            $strRet .= $this->showThumbNote($json, $this->group['id'], null);
        }
        $strRet .= "</div>";  // results
        $strRet .= "</div>";  // groupNotes
        return $strRet;
    }
    
    public function folderHeader() {
        $strRet = "";
        $strRet .= "<div id=\"folderHeader\" title=\"hlavička článku\" onclick=\"window.location='?page=group&id={$this->group['id']}&show=folder&idFolder={$this->folder['id']}'\">";
        $strRet .= "<h3>{$this->folder['name']}</h3>";
        $strRet .= "</div>";
        $strRet .= "<div id=\"folderHeaderInfo\" title=\"hlavička článku\" onclick=\"window.location='?page=group&id={$this->group['id']}&show=folder&idFolder={$this->folder['id']}'\">";
        $strRet .= "<p>{$this->folder['info']}</p>";
        $strRet .= "</div>";
        return $strRet;
    }
    
    public function getNavigBar () {
        $strRet = "";
        $N = $_GET;
        $strRet .= "<div id=\"navigBar\">";
        if (isset ($N['page']) && $N['page'] == 'group') {
            $strRet .= "<div id=\"navigBarItemHome\">";
            $strRet .= "<a href=\"?page=group&id={$this->group['id']}";
            if (isset($N['show'])) $strRet .= "&show={$_GET['show']}";
            $strRet .="\">";
            if(isset($N['show']) && $N['show'] == 'folder') {
                $strRet .= "<h2>Články</h2>";
                $strRet .= "</a>";
                $strRet .= "</div>";  // navigBarItemHome
                if (isset ($N['idFolder'])) {
                    if ($N['idFolder'] != 0) {
                        $strRet .= "<div class=\"navigBarItem\">";
                        $strRet .= "<a href=\"?page=group&id={$this->group['id']}&show={$_GET['show']}&idFolder={$N['idFolder']}\">";
                        $strRet .= $this->folder['name'];
                        $strRet .= "</a>";
                        $strRet .= "</div>";
                    }
                    if (isset ($N['showNote'])) {
                        $noteDb = mysql_fetch_array(mysql_query("SELECT * FROM `note` WHERE id = {$N['showNote']}"));       
                        $note = simplexml_load_string(file_get_contents($noteDb['path']));
                        $strRet .= "<div class=\"navigBarItem\">";
                        $strRet .= "<a href=\"{$_SERVER['REQUEST_URI']}\">";
                        $strRet .= $note->children()->KeyWords->children()->KW;
                        $strRet .= "</a>";
                        $strRet .= "</div>";
                     }
                }
            }
            else if(isset($N['show']) && $N['show'] == 'forum') {
                $strRet .= "<h2>Fórum</h2>";
                $strRet .= "</a>";
                $strRet .= "</div>";  // navigBarItemHome
                if (isset ($N['topic'])) {
                    $topic = mysql_fetch_array(mysql_query("SELECT * FROM `topic` WHERE id = {$N['topic']}"));
                    $strRet .= "<div class=\"navigBarItem\">";
                    $strRet .= "<a href=\"{$_SERVER['REQUEST_URI']}\">";
                    $strRet .= $topic['subject'];
                    $strRet .= "</a>";
                    $strRet .= "</div>";
                }
            }
            else if(isset($N['show']) && $N['show'] == 'files') {
                $strRet .= "<h2>Súbory</h2>";
                $strRet .= "</a>";
                $strRet .= "</div>";  // navigBarItemHome
            }
            else if(isset($N['show']) && $N['show'] == 'notes') {
                $strRet .= "<h2>Poznámky</h2>";
                $strRet .= "</a>";
                $strRet .= "</div>";  // navigBarItemHome
                if (isset ($N['showNote'])) {       
                    $note = json_decode(file_get_contents("./groups/{$this->group['id']}/notes/{$N['showNote']}.json"), true);
                    $strRet .= "<div class=\"navigBarItem\">";
                    $strRet .= "<a href=\"{$_SERVER['REQUEST_URI']}\">";
                    $strRet .= $note['KeyWords'][0];
                    $strRet .= "</a>";
                    $strRet .= "</div>";
                }
            }
            else if(!isset($N['show']) || $N['show'] == 'news') {
                $strRet .= "<h2>Novinky</h2>";
                $strRet .= "</a>";
                $strRet .= "</div>";  // navigBarItemHome
            } 
            else if($N['show'] == 'search') {
                $strRet .= "<h2>Vyhľadávanie v skupine</h2>";
                $strRet .= "</a>";
                $strRet .= "</div>";  // navigBarItemHome
            } else {
                $strRet .= "<h2>Novinky</h2>";
                $strRet .= "</a>";
                $strRet .= "</div>";  // navigBarItemHome
            }      
        }
        $strRet .= $this->getBasicSearch();
        $strRet .= "</div>";   //navigBar
        return $strRet;    
    }    
    
    public function showThumbFolder ($folder, $user) {
        $strRet = "";
        $strRet .= "<div id=\"";
        if ($folder['id_user'] != '0') $strRet .= "folderThumb";
        else $strRet .= "folderThumbAll";
        $strRet .= "\" onclick=\"window.location='?page=group&id={$folder['id_group']}&show=folder&idFolder={$folder['id']}'\" class=\"";
        if ($folder['public'] == '1') $strRet .= "open";
        else $strRet .= "closed";
        $strRet .="\">";
        $strRet .= "<h3>".$folder['name']."</h3>";
        $strRet .= "<p>".$folder['info']."</p>";
        $strRet .= "<span>{$user['name']} {$user['surname']}</span>";
        $strRet .= "</div>";
        return $strRet;
    }

    public function showArticles() {
        $strRet = "";
        $strRet .= $this->getFilter();      
        $strRet .= "<div id=\"groupFolders\">";
        if ($this->memberOfGroup || $this->adminOfGroup) {                                                                          
            $strRet .= "<button type=\"button\" class=\"btn btn-success\" title=\"vytvoriť článok\" onclick=\"window.location='{$_SERVER['REQUEST_URI']}&edit=true&type=noteVisualAdd'\">+ Nový článok</button>";
            $strRet .= "<button type=\"button\" class=\"btn btn-primary\" title=\"vytvoriť novú zložku s článkami\" onclick=\"window.location='?page=group&show=folder&edit=true&type=folderAdd&id={$this->group['id']}'\">+ Nová zložka</button>";
        }
        $strRet .= "<div id=\"FAchanger\">";
        $strRet .= "Články <input type=\"radio\" name=\"changer\" value=\"article\" id=\"articleChanger\" checked>";
        $strRet .= "Zložky s článkami<input type=\"radio\" name=\"changer\" value=\"folder\" id=\"folderChanger\">";
        $strRet .= "</div>";    // FAchanger
        $strRet .= "<div id=\"results\" class=\"resultsArticles\">";
        /* ROZNE NOTY                                         --> PRAVDEPODOBNE ZMAZAT
        $strRet .= "<div id=\"folderThumbAll\" onclick=\"window.location='{$_SERVER['REQUEST_URI']}&idFolder=0'\" class=\"open\">";
        $strRet .= "<h3>Rôzne</h3>";
        $strRet .= "<p>Všetky články bez zložky</p>";
        $strRet .= "</div>";  */
                    
        $path = "./groups/{$this->group['id']}/articles/";
        $files = scandir($path);
        $files = array_reverse($files);
        $sql = "SELECT * FROM `note` WHERE visual = '1' AND id_group = '{$this->group['id']}' ORDER BY date DESC";
        $result = mysql_query($sql);
        while ($article = mysql_fetch_array($result)) {
            $json = json_decode(file_get_contents($article['path']), true);
            $strRet .= $this->showThumbNote($json, $this->group['id'], $article['id_folder']);  
        }
       /* ZOBRAZENIE ZLOZIEk, TREBA VYTVORIT OSOBITNU FUNKCIU // 
        foreach ($files as $folder) {
            if ($folder === '.' or $folder === '..') continue;
            if (is_dir($path . '/' . $folder)) {
                $sql = "SELECT * FROM `folder` WHERE id = {$folder} AND id_group = {$this->group['id']}";
                $result = mysql_query($sql);
                $tmp = mysql_fetch_array($result);
                $sqlUser = "SELECT * FROM `user` WHERE id = {$tmp['id_user']}";
                $user = mysql_fetch_array(mysql_query($sqlUser));
                if ($tmp) {
                    $strRet .= $this->showThumbFolder($tmp, $user);
                }
            }
        }     */  
        $strRet .= "</div>";      // results
        $strRet .= "</div>";      // groupFolders 
        return $strRet;
    }
    
    public function showTopic($id) {
        $strRet = "";
        $sqlViews = "UPDATE `topic` SET views = views + 1 WHERE `id` = {$id}";
        $result = mysql_query($sqlViews);
        
        
        $sqlTopic = "SELECT * FROM `topic` WHERE id = {$id}";
        $topic = mysql_fetch_array(mysql_query($sqlTopic));
        $phpdate = strtotime( $topic['date'] );
        $mysqldate = date( 'd. m. Y H:i', $phpdate );
        $sqlAuthor = "SELECT * FROM `user` WHERE id = {$topic['id_user']}";
        $author = mysql_fetch_array(mysql_query($sqlAuthor));
        $sql = "SELECT * FROM `reply` WHERE id_topic = {$id}";
        $result = mysql_query($sql);
        
        $strRet .= "<div id=\"topic\">";
        $strRet .= "<div id=\"topicSubject\" title=\"predmet\">";
        $strRet .= "Predmet: <h3>{$topic['subject']}</h3>";
        $strRet .= "<span>";
        if($author['id'] == $this->user['id']) {
            $strRet .= "<button class=\"btn btn-default btn-xs\" title=\"vymazať tému\" onclick=\"conf('?page=action&type=topicDel&groupId={$this->group['id']}&topicId={$topic['id']}')\">X</button>";
            $strRet .= "<button class=\"btn btn-default btn-xs topicEditBtn\" title=\"upraviť tému\">&#10000</button>";
        }
        $strRet .= "{$mysqldate}</span>";
        $strRet .= "</div>";
        
        while($reply = mysql_fetch_array($result)) {
            $sqlUser = "SELECT * FROM `user` WHERE id = {$reply['id_user']}";
            $user = mysql_fetch_array(mysql_query($sqlUser));
            $phpdate = strtotime( $reply['date'] );
            $mysqldate = date( 'd. m. Y H:i', $phpdate );
            $strRet .= "<div id=\"topicReply\" title=\"reakcia\">";
            $strRet .= "<div id=\"replyUser\">";
            $strRet .= "<img src=\"users/{$user['id']}/userPhoto.jpg\" width=\"80\" height=\"60\" class=\"img-thumbnail\"><br>";
            $strRet .= $user['name']. " " .$user['surname'];
            $strRet .= "</div>";
            $strRet .= "<div id=\"replyContent\" class=\"{$reply['id']}\"><div>".$reply['content']."</div>";
            $strRet .= "<span>";
            if($user['id'] == $this->user['id']) {
                $strRet .= "<button class=\"btn btn-default btn-xs\" title=\"vymazať reakciu\" onclick=\"conf('?page=action&type=replyDel&groupId={$this->group['id']}&replyId={$reply['id']}&topicId={$reply['id_topic']}')\">X</button>";
                $strRet .= "<button class=\"btn btn-default btn-xs replyEditBtn\" title=\"upraviť reakciu\" name=\"{$reply['id']}\">&#10000</button>";
            }
            $strRet .= "{$mysqldate}</span>";
            $strRet .= "</div>";
            if($user['id'] == $this->user['id']) {
            // HIDE
                $strRet .= "<div id=\"replyEdit\" class=\"{$reply['id']}\">";
                $strRet .= "<form action=\"?page=action&type=editReply&replyId={$reply['id']}&groupId={$this->group['id']}&topicId={$reply['id_topic']}\" method=\"post\">";
                $strRet .= "<textarea required name=\"content\" class=\"forumAddContent\">{$reply['content']}</textarea><br>";
                $strRet .= "<input type=\"submit\" value=\"Odošli\" class=\"btn btn-success\">";
                $strRet .= "<button type=\"button\" class=\"btn transparent exitReplyEdit\" name=\"{$reply['id']}\">Spať</button>";
                $strRet .= "</form>";
                $strRet .= "</div>";
            // END OF HIDE
            }
            
            $strRet .= "</div>";
        }
        $strRet .= $this->replyTopic($_GET['topic'], $this->user['id']);
        $strRet .= "<button type=\"button\" title=\"reagovať\" class=\"btn btn-primary btnAddReply\">Reagovat</button>" ;
        
        $strRet .= "</div>";
        
        return $strRet;
    }
    
    public function replyTopic($topicId, $userId) {
        $strRet = "";
        // HIDE
        $strRet .= "<div id=\"addReply\">";
        $strRet .= "<form action=\"?page=action&type=replyTopic&userId={$this->user['id']}&topicId={$_GET['topic']}&groupId={$this->group['id']}\" method=\"post\">";
        $strRet .= "<textarea required name=\"content\" class=\"forumAddContent\" placeholder=\"Telo spravy\"></textarea><br>";
        $strRet .= "<input type=\"submit\" value=\"Odošli\" class=\"btn btn-success\">";
        $strRet .= "</form>";
        $strRet .= "</div>";
        return $strRet; 
    }
    
    public function getForumTableRow($topic,$grp) {
        $strRet = "";  
        $sqlReply = "SELECT * FROM `reply` WHERE id_topic = {$topic['id']}";
        $answers = mysql_num_rows(mysql_query($sqlReply)) - 1; 
        if ($_GET['page'] == 'home') {
            $sqlGroup = "SELECT * FROM `group` WHERE id = {$topic['id_group']}";
            $group = mysql_fetch_array(mysql_query($sqlGroup));
        } else {
            $sqlUser = "SELECT * FROM `user` WHERE id = {$topic['id_user']}";
            $user = mysql_fetch_array(mysql_query($sqlUser));
            $group = $grp;
        }
        $strRet .= "<tr class=\"table table-striped forumRow\" href=\"index.php?page=group&id={$group['id']}&show=forum&topic={$topic['id']}\">";
        $strRet .= "<td class=\"table tableSubject table-striped\">";
        $strRet .= "<a href=\"index.php?page=group&id={$group['id']}&show=forum&topic={$topic['id']}\">";
        $strRet .= "<div>{$topic['subject']}</div>";
        $strRet .= "</a></td>";
        $strRet .= "</td>";
        if ($_GET['page'] == 'home') $strRet .= "<td>{$group['name']}</td>";
        else $strRet .= "<td>{$user['name']} {$user['surname']}</td>";
        $strRet .= "<td>{$topic['views']}</td>"
                .  "<td>{$answers}</td>"
                .  "<td>{$topic['date']}</td>";
        $strRet .= "</tr>";
        return $strRet;
    }
    
    public function getForumTable($result) {
        $strRet = "";
        if ($_GET['page'] == 'home') {
            $tmp = "Skupina";
        } else {
            $tmp = "Autor";
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
            $strRet .= $this->getForumTableRow($topic, $this->group);
        }
        $strRet .= "</table>";
        return $strRet;
    }
    
    public function showForum() {
        $strRet = "";
        $strRet .= $this->getFilter(); 
        $strRet .= "<div id=\"forum\">";
        if ($this->memberOfGroup || $this->adminOfGroup) {    
            $strRet .= "<a href=\"?page=group&id={$this->group['id']}&edit=true&type=topicAdd&show=forum\"><button type=\"button\" class=\"btn btn-primary\" title=\"vytvoriť novú tému\">+ Nová téma</button></a>";
            $sql = "SELECT * FROM `topic` WHERE id_group = {$this->group['id']} ORDER BY date DESC";
            $result = mysql_query($sql);
            $strRet .= "<div id=\"results\" class=\"resultsForum\">";
            $strRet .= $this->getForumTable($result);
            $strRet .= "</div>";
        } else {
            $strRet .= "Ľutujeme, nemáte dostatočné oprávnenia na zobrazenie tohoto obsahu.";
        }
        $strRet .= "</div>";
        return $strRet;
    }   
    
                                        
    public function getThumbActions() {
        $strRet = "";
        if($this->memberOfGroup || $this->adminOfGroup) {
            $strRet .= "<div id=\"groupThumbActions\">";
            $strRet .= "<div id=\"actionsTitle\">";
            $strRet .= "<h3>Aktuality</h3>";
            $strRet .= "</div>";   //actionsTitle
            $strRet .= "<div id=\"actionsContent\">";   
            $strRet .= "</div>";  // actionsContent        
            $strRet .= "</div>"; // groupThumbActions
        }
        return $strRet;
    }
    
    public function showOneNew($new, $groupId, $userId) {
        $strRet = "";
        $strRet .= "<div id=\"novinkaMain\">";
        $phpdate = strtotime( $new['date'] );
        $mysqldate = date( 'd. m. Y H:i', $phpdate );
        $author = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE id = {$new['id_user']}"));
        $strRet .= "<div id=\"novinka\">";
        $strRet .= "<div id=\"novinkaUser\">";
        $strRet .= "<img src=\"users/{$author['id']}/userPhoto.jpg\" width=\"80\" height=\"60\" class=\"img-thumbnail\"><br>";
        $strRet .= $author['name']. " ". $author['surname'];
        $strRet .= "</div>"; 
        $strRet .= "<div id=\"novinkaContent\" class=\"{$new['id']}\"><div>".$new['content']."</div>";
        $strRet .= "<span>";
        $cnt = mysql_num_rows(mysql_query("SELECT * FROM `comment` WHERE id_news = {$new['id']}"));
        $sqlFile = "SELECT * FROM `file` WHERE id_news = {$new['id']}";
        $resultFile = mysql_query($sqlFile);
        $file = mysql_fetch_array($resultFile);
        if ($file) {         // novinka so suborom
            $filename = substr(strrchr($file['path'], "/"), 1);
            $type = substr(strrchr($filename, "."), 1);
            $strRet .= "<a href=\"{$file['path']}\" ";
            if ($type == 'jpg' || $type == 'gif' || $type == 'png' || $type == 'jpeg') {
                $strRet .= "class=\"fancybox\">";
            } 
            else $strRet .= "download>";
            $strRet .= $filename;
            $strRet .= "</a>";
        }
        $strRet .= "<button class=\"btn btn-default btn-xs commentsBtn\" title=\"komentáre\" name=\"{$new['id']}\">Komentáre($cnt)</button>";
        if($author['id'] == $userId) {
            $strRet .= "<button class=\"btn btn-default btn-xs\" title=\"vymazať novinku\" onclick=\"conf('?page=action&type=newsDel&groupId={$groupId}&newsId={$new['id']}')\">X</button>";
            $strRet .= "<button class=\"btn btn-default btn-xs editNewsBtn\" title=\"upraviť novinku\" name=\"{$new['id']}\">&#10000</button>";
        }
        $strRet .= "{$mysqldate}</span>";
        $strRet .= "</div>";   // novinkaContent
        if($author['id'] == $userId) {
        // HIDE  edit
            $strRet .= "<div id=\"novinkaEdit\" class=\"{$new['id']}\">";
            $strRet .= "<form method=\"post\" action=\"?page=action&type=newsEdit&userId={$userId}&groupId={$groupId}&novinka={$new['id']}\">";
            $strRet .= "<textarea required name=\"content\">{$new['content']}</textarea><br>";
            $strRet .= "<button type=\"submit\" class=\"btn btn-success\">Pridať</button>";
            $strRet .= "<button type=\"button\" class=\"btn transparent exitEditNews\" name=\"{$new['id']}\">Spať</button>";
            $strRet .= "</form>";
            $strRet .= "</div>";
        // END OF HIDE
        }                  
        $strRet .= "</div>";    // novinka
        $strRet .= $this->getComments($new, $groupId, $userId);
        $strRet .= "</div>";   // novinkaMain
        return $strRet;
    }
    
    public function getComments($new, $groupId, $userId){
        $strRet .= "<div id=\"novinkaComments\" class=\"{$new['id']}\">";
        $strRet .= "<div id=\"addComment\">";
        $strRet .= "<form action=\"?page=action&type=addComment&userId={$userId}&groupId={$groupId}&newsId={$new['id']}\" method=\"post\">";
        $strRet .= "<textarea name=\"content\" title=\"obsah komentáru\" required placeholder=\"Vlož svoj komentár\"></textarea>";
        $strRet .= "<input type=\"submit\" class=\"btn btn-success\" title=\"pridať komentár\" value=\"Pridať\">";
        $strRet .= "</form>";
        $strRet .= "</div>";
        $strRet .= "<div id=\"showComment\">";
        $sql = "SELECT * FROM `comment` WHERE id_news = {$new['id']}";
        $result2 = mysql_query($sql);
        while($comment = mysql_fetch_array($result2)) {
            $phpdate = strtotime( $comment['date'] );
            $mysqldate = date( 'd. m. Y H:i', $phpdate );
            $author = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE id = {$comment['id_user']}"));
            $strRet .= "<div id=\"comment\" title=\"komentár\">";
            $strRet .= "<div id=\"commentUser\">"; 
            $strRet .= "<img src=\"users/{$author['id']}/userPhoto.jpg\" class=\"img-thumbnail\"><br>";
            $strRet .= $author['name']. " ". $author['surname'];
            $strRet .= "</div>";           //commentUser
            $strRet .= "<div id=\"commentContent\" class=\"{$comment['id']}\">";
            $strRet .= "<div>".$comment['content']."</div>";
            $strRet .= "<span>";
            if($author['id'] == $userId) {
                $strRet .= "<button class=\"btn btn-default btn-xs\" title=\"vymazať komentár\" onclick=\"conf('?page=action&type=delComment&groupId={$groupId}&commentId={$comment['id']}')\">X</button>";
                $strRet .= "<button class=\"btn btn-default btn-xs editCommentBtn\" title=\"upraviť komentár\" name=\"{$comment['id']}\">&#10000</button>";
            }
            $strRet .= $mysqldate;
            $strRet .= "</span>";
            $strRet .= "</div>"; //commentContent
            // HIDE
            $strRet .= "<div id=\"commentEdit\" class=\"{$comment['id']}\">";
            $strRet .= "<form action=\"?page=action&type=editComment&commentId={$comment['id']}&groupId={$grorupId}\" method=\"post\">";
            $strRet .= "<textarea name=\"content\" required>{$comment['content']}</textarea>";
            $strRet .= "<input type=\"submit\" class=\"btn btn-success\" value=\"odosli\">";
            $strRet .= "<button type=\"button\" class=\"btn transparent exitEditComment\" name=\"{$comment['id']}\">Spať</button>";
            $strRet .= "</form>";
            $strRet .= "</div>";
            $strRet .= "</div>";    // comment
        }
        
        $strRet .= "</div>";   // showComment
        $strRet .= "</div>";   // novinkaComments
        return $strRet;
    }
     
    public function showNews() {
        $strRet = "";
        $strRet .= "<div id=\"groupNews\">";
        if($this->memberOfGroup || $this->adminOfGroup) {
            $strRet .= "<div id=\"groupNewsAdd\">";
            $strRet .= "<form method=\"post\" action=\"?page=action&type=newsAdd&userId={$this->user['id']}&groupId={$this->group['id']}\" enctype=\"multipart/form-data\">";
            $strRet .= "<textarea required placeholder=\"Pridať novinku\" title=\"pridaj novinku\" name=\"content\"></textarea>";
            $strRet .= "<button type=\"submit\" title=\"vytvor novinku\" class=\"btn btn-success\">Pridať</button><br>";
            $strRet .= "<button type=\"button\" title=\"pridaj súbor\" class=\"btn btn-xs btn-success addFileBtn\">+ súbor</button><br><br><br>";
            $strRet .= $this->getAddFileDiv();
            $strRet .= "</form>";
            $strRet .= "</div>";              // groupNewsAdd
            
            $sql = "SELECT * FROM `news` WHERE id_group = {$this->group['id']} ORDER BY id DESC";
            $result = mysql_query($sql);
            if (mysql_num_rows($result) == 0) $strRet .= "<div id=\"noNews\">V tejto skupine zatiaľ nie sú žiadne novinky.</div>";
            else {
                $strRet .= "<div id=\"results\" class=\"resultsNews\">";
                while ($new = mysql_fetch_array($result)) {
                    $strRet .= $this->showOneNew($new, $this->group['id'], $this->user['id']);
                }
                $strRet .= "</div>";
            }    
        } else {
            $strRet .= "Ľutujeme, nemáte dostatočné oprávnenia na zobrazenie tohoto obsahu.";
        }
        $strRet .= "</div>";
        return $strRet;
    }
    
    public function showInfo() {
        $strRet = "";
        if ($this->adminOfGroup) $strRet .= "<span class=\"editInfoBtn\"><button class=\"btn btn-default transparent\">&#10000</button></span>";
        $strRet .= "<div id=\"curInfo\">"
                 . "{$this->group['info']}<br><br>";
        $strRet .= "</div>";
        
        
        // Hide , az po odkliknuti sa zobrazi
        $strRet .= "<div id=\"editInfo\">";
        $strRet .= "<form action=\"?page=action&type=groupEditInfo&groupId={$this->group['id']}\" method=\"post\">";
        $strRet .= cGroupAdd::setInfoGroup($this->group['info']);
        $strRet .= "<input type=\"submit\" value=\"Uprav\" class=\"btn btn-default\">"
                 . "</form>";
        $strRet .= "<button class=\"exitEditInfo btn-edit transparent\">Späť</button>";
        $strRet .= "</div>" ; 
        // end of hide
        return $strRet;
    }
    
    public function showOneFile($file, $userId) {
        $strRet = "";
        $user = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE id = {$file['id_user']}"));
        $filename = substr(strrchr($file['path'], "/"), 1);
        $type = substr(strrchr($filename, "."), 1);
        $strRet .= "<div id=\"fileThumb\">";
        $strRet .= "<a href=\"{$file['path']}\" ";
        if ($type == 'jpg' || $type == 'JPG' || $type == 'JPEG' || $type == 'GIF' || $type == 'PNG' || $type == 'gif' || $type == 'png' || $type == 'jpeg') {
            $strRet .= "class=\"fancybox\">";
            $strRet .= "<img src=\"./images/img.jpg\" class=\"fileType\">";
        } 
        else $strRet .= "download>";
        if ($type == 'doc' || $type == 'dot' || $type == 'docm' || $type == 'dotx' || $type == 'dotm' || $type == 'docx' || $type == 'DOC' || $type == 'DOCX') $strRet .= "<img src=\"./images/doc.jpg\" class=\"fileType\">";
        if ($type == 'pdf') $strRet .= "<img src=\"./images/pdf.jpg\" class=\"fileType\">";
        if ($type == 'mp3' || $type == 'waw' || $type == 'wma' || $type == 'flac' || $type == 'aac' || $type == '3gp' || $type == 'ogg') $strRet .= "<img src=\"./images/music.jpg\" class=\"fileType\">";
        if ($type == 'xlsx' || $type == 'xls' || $type == 'xlsm' || $type == 'xlsb'  || $type == 'xltx' || $type == 'xltm'  || $type == 'xlt' || $type == 'xml' || $type == 'xlam'  || $type == 'xla' || $type == 'xlw') $strRet .= "<img src=\"./images/excel.jpg\" class=\"fileType\">";
        if ($type == 'ppt' || $type == 'pps' || $type == 'ppsx' || $type == 'pptx') $strRet .= "<img src=\"./images/ppt.jpg\" class=\"fileType\">";
        
        $strRet .= $filename;
        $strRet .= "</a>";
        $strRet .= "<span>";                                                                                   
        if ($file['id_user'] == $userId) $strRet .= "<button class=\"btn btn-xs btn-default\" title=\"vymazať súbor\" onclick=\"conf('?page=action&type=fileDelete&fileId={$file['id']}')\">X</button>";
        if ($file['info'] != null || $userId == $file['id_user']) $strRet .= "<button title=\"informácie o súbore\" class=\"btn btn-xs btn-default showFileInfo\" type=\"button\" name=\"{$file['id']}\">i</button>";
        $size = number_format((float)$file['size'], 0, '.', '');
        $strRet .= $size." KB</span>"; // </span>
        $strRet .= "<div id=\"fileInfo\" class=\"{$file['id']}\">";
        $strRet .= "<div id=\"curFileInfo\" class=\"{$file['id']}\">";
        $strRet .= $file['info'];
        $strRet .= "<span>";
        
        if ($_GET['page'] == 'home') {
            $sqlGroup = "SELECT * FROM `group` WHERE id = {$file['id_group']}";
            $group = mysql_fetch_array(mysql_query($sqlGroup));
            $strRet .= $group['name'];
        }
        else {
            $strRet .= "{$user['name']} {$user['surname']}<br>";
            $strRet  .= $file['category'];
        }
        $strRet .= "</span>";
        if ($file['id_user'] == $userId) $strRet .= "<button title=\"upraviť informácie o súbore\" class=\"btn btn-xs btn-default editFileInfoBtn\" name=\"{$file['id']}\">&#10000</button>";
        $strRet .= "</div>";        // curFileInfo
        if ($file['id_user'] == $userId) {
            $strRet .= "<div id=\"editFileInfo\" class=\"{$file['id']}\">";
            $strRet .= "<form action=\"?page=action&type=editFileInfo&groupId={$groupId}&fileId={$file['id']}\" method=\"post\">";
            $strRet .= "<textarea name=\"info\">{$file['info']}</textarea>";
            $strRet .= "<button type=\"button\" class=\"btn btn-default btn-edit transparent btn-xs exitEditFileInfoBtn\" name=\"{$file['id']}\">Spät</button>";
            $strRet .= "<input type=\"submit\" value=\"Uprav\" class=\"btn btn-default btn-xs btn-primary\">";
            $strRet .= "</form>";
            $strRet .= "</div>";        // editFileInfo
        }
        $strRet .= "</div>";        // fileInfo
        $strRet .= "</div>";      // fileThumb 
        return $strRet;
    }
    
    public function getSearchThumb() {
        $strRet = "";
        $strRet .= "<div id=\"groupSearchThumb\">";
        $strRet .= "<input type=\"text\" placeholder=\"vyhľadať v skupine\" class=\"groupSearchInput searchBoxInput\">";
        $strRet .= "<button id=\"searchBtn\" class=\"searchBtnBlue\"><img src=\"./images/search_20px\"></button>";
        $strRet .= "</div>";    //groupSearchThumb
        return $strRet;
    }
    
    public function getSearch() {
        $strRet = "";
        $strRet .= "<div id=\"search\">";
        $strRet .= "<div id=\"groupSearch\">";
        $strRet .= "<input type=\"hidden\" value=\"{$this->user['id']}\" id=\"userId\"/>"; // premenna do Javascriptu
        $strRet .= "<input type=\"text\" name=\"searchBox\" class=\"searchBox\">";
        $strRet .= "<button class=\"btn btn-primary btn-xs\" id=\"searchBtn\">hľadaj</button><br />";
        $strRet .= "poznámky a články:<input type=\"checkbox\" name=\"notes\" id=\"chNotes\" class=\"searchProperties\" checked> ";
        $strRet .= "novinky:<input type=\"checkbox\" name=\"news\" id=\"chNews\" class=\"searchProperties\" checked> ";
        $strRet .= "súbory:<input type=\"checkbox\" name=\"files\" id=\"chFiles\" class=\"searchProperties\" checked> ";
        $strRet .= "fórum:<input type=\"checkbox\" name=\"forum\" id=\"chForum\" class=\"searchProperties\" checked> ";
        $strRet .= "</div>";    // groupSearch
        $strRet .= "<div id=\"results\">";
        $strRet .= "</div>";
        $strRet .= "</div>";    // search
        return $strRet;
    }
          
    public function getGroupContent() {
        $strRet = "";
        $strRet .= "<div id=\"groupContent\">";
        if ($_GET['show'] != 'settings') $strRet .= $this->getNavigBar();
        if (isset ($_GET['showNote'])) {    
            if(isset ($_GET['idFolder']) && $_GET['idFolder'] != 0) $strRet .= $this->folderHeader();
            $strRet .= $this->showNote($_GET['showNote']);
        }
        else if ($_GET['edit'] == 'true') {
            $editGroup = new cEditGroup($this->group['id']);
            $strRet .= $editGroup->getType();
        }
        else if ($_GET['show'] == 'folder') {
            if (isset($_GET['idFolder'])) {
                $strRet .= $this->showArticlesInFolder();       
            }
            else $strRet .= $this->showArticles();
        }
        else if ($_GET['show'] == 'forum') {
            if (isset($_GET['topic'])) $strRet .= $this->showTopic($_GET['topic']);
            else $strRet .= $this->showForum();
        }
        else if ($_GET['show'] == 'news' || !isset($_GET['show']) || $_GET['show'] == null) {
            $strRet .= $this->showNews();
        }
        else if ($_GET['show'] == 'search') {
            $strRet .= $this->getSearch();
        }
        else if ($_GET['show'] == 'files') $strRet .= $this->showFiles();
        else if ($_GET['show'] == 'info') $strRet .= $this->showInfo();
        else if ($_GET['show'] == 'notes') $strRet .= $this->showNotes();
        else if ($_GET['show'] == 'settings') {
            $groupSettings = new cGroupSettings($this->group['id']);
            $strRet .= $groupSettings->getContent();
        }
        else $strRet .= $this->showNews();
        $strRet .= "</div>";
        return $strRet;
    }
    
    public function getAddFileDiv() {
        $strRet = "";
        $sqlCategory = "SELECT * FROM `category` WHERE id_group = {$this->group['id']}";
        $resCategory = mysql_query($sqlCategory);
        $strRet .= "<div id=\"addFile\">";
        if ($_GET['show'] == 'files') $strRet .= "<form method=\"post\" action=\"?page=action&type=fileAdd&groupId={$this->group['id']}&userId={$this->user['id']}\" enctype=\"multipart/form-data\">";
        $strRet .= "<input type=\"file\"";   
        if ($_GET['show'] == 'files') $strRet .= " required";
        $strRet .= " name=\"file\" value=\"subor\">";
        $strRet .= "<textarea placeholder=\"informácie o súbore\" name=\"info\"></textarea>";
        $strRet .= "<select name=\"category\" title=\"vybrať kategóriu\" id=\"selectFileCategories\">";
        $strRet .= "<option value=\"nezaradené\">(nezaradené)</option>";
        while ($category = mysql_fetch_array($resCategory)) {
            $strRet .= "<option value=\"{$category['name']}\">";
            $strRet .= $category['name'];
            $strRet .= "</option>";
        }
        $strRet .= "</select>";
        $strRet .= " zaradiť do kategórie";
        $strRet .= "<h4>pridať nový súbor</h4>";
        $strRet .= "<button type=\"button\" class=\"btn btn-default exitAddFileBtn transparent btn-edit\">Späť</button>";
        if ($_GET['show'] != 'news' && isset($_GET['show']))$strRet .= "<button type=\"submit\" title=\"pridať súbor\" class=\"btn btn-success\">Pridať</button><br>";
        if ($_GET['show'] != 'news')$strRet .= "</form>";
        $strRet .= "</div>";    // addfile hide
        return $strRet;
    }
    
    public function getFilter() {
        $sqlCategory = "SELECT * FROM `category` WHERE id_group = {$this->group['id']}";
        $strRet = "";
        $strRet .= "<div id=\"filter\">";
        $strRet .= "Filter:";
        $strRet .= "<select id=\"selectCategories\" title=\"vybrať kategóriu\" >";
        $strRet .= "<option value=\"all\">(Všetky)</option>";
        $resCategory = mysql_query($sqlCategory);
        while ($category = mysql_fetch_array($resCategory)) {
            $strRet .= "<option value=\"{$category['name']}\" title=\"{$category['name']}\">";
            $strRet .= $category['name'];                     
            $strRet .= "</option>";
        }
        $strRet .= "<option value=\"nezaradené\">(nezaradené)</option>";
        $strRet .= "</select>";
        $strRet .= "</div>";      // filter
        return $strRet;
    }
    
    public function showFiles() {
        $strRet = "";        
        $sql = "SELECT * FROM `file` WHERE id_group = {$this->group['id']} ORDER BY id DESC";
        $result = mysql_query($sql);
        $strRet .= $this->getFilter();
        $strRet .= "<div id=\"files\">";
        $strRet .= "<button type=\"button\" title=\"pridať nový súbor\" class=\"btn btn-default addFileBtn btn-primary\">+ Nový súbor</button>";
        $strRet .= $this->getAddFileDiv();
        $strRet .= "<div id=\"results\" class=\"resultsFiles\">" ;       
        include "showFiles.php";
        $strRet .= "</div>";      // results
        $strRet .= "</div>";    // files
        return $strRet;
    }
                                                                        
    
    public function getButtons() {
        $show = $_GET['show'];
        $strRet = "";
        $strRet .= "<div id=\"groupButtons\">";
        $strRet .= "<div id=\"mainButtons\">";
        $strRet .= "<div class=\"menuBtn";
        if (!isset($show) || $show == 'news' || $show == null || ($show != 'notes' && $show != 'folder' && $show != 'forum' && $show != 'files')) {
            $strRet .=" active\"";
        } else $strRet .=  "\"";
        $strRet .= "onclick=\"window.location='?page=group&id={$this->group['id']}'\">NOVINKY</div>";
        $strRet .= "<div class=\"menuBtn";
        $strRet .= ($_GET['show'] == 'notes' ?" active\"" : "\"");
        $strRet .= "onclick=\"window.location='?page=group&id={$this->group['id']}&show=notes'\">POZNÁMKY</div>";        
        $strRet .= "<div class=\"menuBtn";
        $strRet .= ($_GET['show'] == 'folder' ?" active\"" : "\"");
        $strRet .= "onclick=\"window.location='?page=group&id={$this->group['id']}&show=folder'\">ČLÁNKY</div>";
        $strRet .= "<div class=\"menuBtn";
        $strRet .= ($_GET['show'] == 'forum' ?" active\"" : "\"");
        $strRet .= "onclick=\"window.location='?page=group&id={$this->group['id']}&show=forum'\">FÓRUM</div>";
        $strRet .= "<div class=\"menuBtn";
        $strRet .= ($_GET['show'] == 'files' ?" active\"" : "\"");
        $strRet .= "onclick=\"window.location='?page=group&id={$this->group['id']}&show=files'\">SÚBORY</div>";
        $strRet .= "</div>";   // mainButtons
        /*$strRet .= "<div id=\"rightButtons\">";
        $strRet .= "<div class=\"menuBtnRight";
        $strRet .= ($_GET['show'] == 'settings' ?" active\"" : "\"");
        $strRet .= "title=\"súbory\" onclick=\"window.location='?page=group&id={$this->group['id']}&show=settings'\">";
        $strRet .= "<img src=\"./images/settings.png\">";
        $strRet .= "</div>";
        $strRet .= "<div class=\"menuBtnRight";
        $strRet .= ($_GET['show'] == 'search' ?" active\"" : "\"");
        $strRet .= "title=\"súbory\" onclick=\"window.location='?page=group&id={$this->group['id']}&show=search'\">";
        $strRet .= "<img src=\"./images/lupa.png\">";
        $strRet .= "</div>";
        $strRet .= "</div>";  // rightButtons    */
        $strRet .= "</div>";  // groupButtons       
        return $strRet;        
    }
    
    public function getContent() {
        $strRet = "";
        if ($this->group == null) {                        // Skupina neexistuje
            $strRet .= "Ľutujeme nenašli sme požadovanú skupinu.";
        } else {
                if (!$this->memberOfGroup && !$this->adminOfGroup) {
                    if($this->sentReq) {
                        $strRet .= "Žiadost o členstvo skupiny zaslaná.";
                    } else {
                        $strRet .= "<a href=\"?page=action&type=memberRequest&userId={$this->user['id']}&groupId={$this->group['id']}\">";
                        $strRet .= "<input type=\"button\" class=\btn btn-success\" value=\"+ stať sa členom\">";
                        $strRet .= "</a>";
                    }
                }
                if ($this->adminOfGroup && $_SESSION['acceptRequest']) {
                    $strRet .= "Uživateľ úspešne pridaný do skupiny.";
                    unset($_SESSION['acceptRequest']);
                }
                if ($_SESSION['action_ok'] == true) {
                    unset($_SESSION['action_ok']);
                    $strRet .= "<div id=\"actionOk\">";
                    $strRet .= "akcia úspešne vykonaná";
                    $strRet .= "</div>";
                }
                if ($this->adminOfGroup) $strRet .= $this->getRequest();
                $strRet .= "<div id=\"group\">";
                $strRet .= $this->getSearchThumb();
                $strRet .= $this->getButtons();   
                $strRet .= $this->getGroupContent();
                $strRet .= "</div>"; // group
                $strRet .= "<div id=\"groupSidePanel\">";
                $strRet .= "<div id=\"groupSidePanelInfo\">";
                $strRet .= "<div id=\"PhotoTitle\">";                                
                $strRet .= $this->groupPhoto();
                $strRet .= $this->groupTitle();     
                $strRet .= "</div>";    // photoTitle           
                $strRet .= $this->groupInfo();
                $strRet .= "</div>";   // groupSidePanelInfo                                                
                $strRet .= $this->getThumbActions();
                //$strRet .= $this->groupMembers();
                $strRet .= "</div>";   // groupSidePanel                
                
            
        }
        return $strRet;
    }
}
