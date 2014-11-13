<?php
class cEditGroup extends cGroup {
    private $type;
    public function __construct($id) {
        parent::__construct($id);
        $this->type = $_GET['type'];
    }
    // if isset $noteId => edit Note
    // else             => add New Note
    public function noteAdd($visual, $noteId) {
        $strRet = "";
        if ($noteId) {
            $sql = "SELECT * FROM `note` WHERE id = {$noteId}";
            $result2 = mysql_query($sql);
            $note = mysql_fetch_array($result2); 
            $file = $note['path'];
            if ($file != null) {
                $tmp = file_get_contents($file);
                $json = json_decode($tmp, true);
                $userId = $json['UserId'];
                $sql = "SELECT * FROM `user` WHERE id = {$userIdr}";
                $result = mysql_query($sql);
                $user = mysql_fetch_array($result);
            }
        }                                                                                  
        if ($visual && $this->folder['public'] == 0 && $this->folder['id_user'] != $this->user['id']) {
            if (isset($_GET['folderId']))
                header('Location: index.php'); 
        }
        $strRet .= "<div id=\"noteAdd\">";
        $strRet .= "<h4>pridať novú poznámku</h4>";
        $strRet .= "<form method=\"post\" ";
        if ($noteId) {          // jedna sa o edit
            $strRet .= "action=\"?page=action&type=noteEdit&userId={$this->user['id']}&noteId={$noteId}&groupId={$this->group['id']}";
            if ($visual) {
                $strRet .= "&visual=true&folderId=";
                if (isset($_GET['idFolder'])) $strRet .= $_GET['idFolder'];
                else {
                    $tmp = mysql_fetch_array(mysql_query("SELECT * FROM `folder` WHERE id_group = {$this->group['id']} AND id_user = '0'"));
                    $strRet .= $tmp['id'];
                }
            }
            $strRet .= "\" enctype=\"multipart/form-data\"";
            if (!$visual) $strRet .= " class=\"textareaForm\"";
            $strRet .= ">";
        }
        else if ($visual) {                  // jedna sa o pridanie noveho obrazoveho notu
            $strRet .= "action=\"?page=action&type=noteVisualAdd&userId={$this->user['id']}&groupId={$this->group['id']}&folderId=";
            if (isset($_GET['idFolder'])) $strRet .= $_GET['idFolder'];
             else {
                    $tmp = mysql_fetch_array(mysql_query("SELECT * FROM `folder` WHERE id_group = {$this->group['id']} AND id_user = '0'"));
                    $strRet .= $tmp['id'];
            }
            $strRet .= "\" enctype=\"multipart/form-data\">";
        } else {                            // jedna sa o pridanie notu
            $strRet .= "action=\"?page=action&type=noteAdd&userId={$this->user['id']}&groupId={$this->group['id']}\" id=\"noteAddForm\">";
        } 
        if ($visual) {
            $strRet .= "<textarea name=\"content\" placeholder=\"obsah článku\" class=\"tinymce\">";
            if ($noteId) $strRet .= $json['Content'];
            $strRet .= "</textarea>";
        } else {
            $strRet .= "<textarea name=\"content\" placeholder=\"obsah poznámky\">";       
            if ($noteId) {
                if (!$visual) $json['Content'] = str_replace("<br />", "", $json['Content']);
                $strRet .= $json['Content'];
            }
            $strRet .= "</textarea>";
        }
        $strRet .= "<br />";
        if ($visual) {
            $strRet .= "<button type=\"button\" class=\"showMakeReference btn btn-default\">Vytvor náhľad</button>";
            $strRet .= "<br>";
        }
        $strRet .= "<div id=\"keywords\" title=\"kľúčové slová\">";
        $strRet .= "Kľúčové slová <Br>";
        if ($noteId) {
            $i = 0;
            foreach ($json['KeyWords'] as $keyword) {
                $strRet .= "<input type=\"text\" name=\"keywords[]\" ";
                if ($i < 1) $strRet .= "required ";
                $strRet .= "value=\"{$keyword}\"><br>";
                $i++;
            }        
        }
        else $strRet .= "<input type=\"text\" required name=\"keywords[]\" placeholder=\"kľúčové slovo - nadpis\">";
        $strRet .= "<span onclick=\"addKW()\" title=\"pridať nové kľučové slovo\" class=\"addKW btn btn-default btn-xs\">+</span><br>";
        $strRet .= "</div>";                // KEYWORDS
        if ($visual) {                      // ak sa jedna o clanok
            $strRet .= "<div id=\"addImages\"><ol id=\"images\">";
            $strRet .= "<li><input type=\"file\" name=\"images[]\" onchange=\"addPic()\"></li>";
            $strRet .= "</ol>";
            if ($noteId) {
                $strRet .= "<div id=\"curImgs\">";
                $sqlImg = "SELECT * FROM `image` WHERE id_note = {$noteId}";
                $res = mysql_query($sqlImg);
                $c = 0;
                while ($img = mysql_fetch_array($res)) {
                    if ($c % 2 == 0 && $c != 0) $strRet .= "<br>";
                    $c++;
                    $strRet .= "<div id=\"imgThumbEdit\">";     
                    $strRet .= "<img src=\"{$img['path_thumb']}\">";
                    $strRet .= "<a href=\"?page=action&type=imgDel&imgId={$img['id']}\"><span class=\"red\">X</span>";
                    $strRet .= "</a>";
                    $strRet .= "</div>";   //imgThumbEdit
                }
                $strRet .= "</div>"; // curImgs
            }
            $strRet .= "</div>";     // addImages
        }
        $strRet .= "<div id=\"referencesAdd\" title=\"použité zdroje\">";
        $strRet .= "Použité zdroje <Br>";
        if ($noteId) {
            foreach ($json['References'] as $ref) {
                $strRet .= "<input type=\"text\" name=\"references[]\" ";
                $strRet .= "value=\"{$ref}\"><br>";
            }   
        }
        else $strRet .= "<input type=\"text\" name=\"references[]\" placeholder=\"použité zdroje\">";
        $strRet .= "<span onclick=\"addRef()\" title=\"pridať nový zdroj\" class=\"addRef btn btn-default btn-xs\">+</span><br>";
        $strRet .= "</div>"; // referencesAdd
        if ($visual) {
            if ($noteId) $strRet .= $this->addCategory($note['category']);
            else $strRet .= $this->addCategory(null);
        }
        $strRet .= "<br>";
        $strRet .= "<input type=\"submit\" class=\"btn btn-success\" ";
        if($_GET['show'] == 'folder') {
            $strRet .= "title=\"pridať článok\"";
        } else $strRet .= "title=\"pridať poznámku\"";
        $strRet .= " value=\"Pridať\">";
        $strRet .= "</form>";
        if ($visual) { 
            $strRet .= "<div id=\"makeReference\">";
            $strRet .= "<input type=\"text\" placeholder=\"cislo obrazku\" class=\"referenceImg\">";
            $strRet .= "<input type=\"text\" placeholder=\"slovo\" class=\"referenceText\">";
            $strRet .= "<button type=\"button\" id=\"makeReferenceBtn\">Sprav</button>";
            $strRet .= "</div>";
        }
        $strRet .= "</div>";
        return $strRet;
    }
    
    public function addCategory($curCategory) {
        $strRet = "";
        $strRet .= "<div id=\"categoryAdd\">";
        $strRet .= "<select name=\"category\" title=\"vybrať kategóriu\" id=\"selectFileCategories\">";
        $strRet .= "<option value=\"nezaradené\">(nezaradené)</option>";
        $sqlCategory = "SELECT * FROM `category` WHERE id_group = {$this->group['id']}";
        $resCategory = mysql_query($sqlCategory);
        while ($category = mysql_fetch_array($resCategory)) {
            $strRet .= "<option value=\"{$category['name']}\"";     
            if($curCategory == $category['name'])
                $strRet .=" selected";
            $strRet .= ">";
            $strRet .= $category['name'];
            $strRet .= "</option>";
        }
        $strRet .= "</select>";
        $strRet .= " zaradiť do kategórie";
        $strRet .= "</div>"; // categoryAdd
        return $strRet;
    }
    
    public function topicAdd() {
        $strRet = "";
        $strRet .= "<div id=\"forumAdd\">";
        $strRet .= "<h4>pridať novú tému</h4>";
        $strRet .= "<form method=\"post\" action=\"?page=action&type=topicAdd&userId={$this->user['id']}&groupId={$this->group['id']}\">";
        $strRet .= "<input type=\"text\" required placeholder=\"predmet\" name=\"subject\" class=\"forumAddSubject\"><br>";
        $strRet .= "<textarea placeholder=\"prvý príspevok\" required name=\"content\" class=\"forumAddContent\"></textarea><br>";
        $strRet .= $this->addCategory(null);
        $strRet .= "<br>";
        $strRet .= "<input type=\"submit\" value=\"Pridať\" class=\"btn btn-success\">";
        $strRet .= "</form>";
        $strRet .= "</div>";
        return $strRet;
    }
    
     public function folderAdd($folderId) {
        $strRet = "";
        if ($folderId) {
            $sql = "SELECT * FROM `folder` WHERE id = {$folderId}";
            $folder = mysql_fetch_array(mysql_query($sql));
            $sql = "SELECT * FROM `folder_reference` WHERE id_folder = {$folderId}";
            $folderReferences = mysql_query($sql);
        }
        $strRet .= "<div id=\"folderAdd\">";
        $strRet .= "<h4>pridať novú zložku s článkami</h4>";
        $strRet .= "<form method=\"post\" action=\"?page=action&type=folderEdit&userId={$this->user['id']}&groupId={$this->group['id']}";
        if ($folderId) $strRet .= "&folderId={$folderId}";
        $strRet .= "\">";
        $strRet .= "<input type=\"text\" required name=\"name\" ";
        if ($folderId) $strRet .= "value=\"{$folder['name']}\"";
        else  $strRet .= "placeholder=\"Názov zložky\"";
        $strRet .= ">";
        $strRet .= "<br><br>";
        $strRet .= "Zatvorená zložka <input type=\"checkbox\" name=\"private\"><Br>";
        $strRet .= "<textarea name=\"info\" required placeholder=\"Úvodné informácie o zložke\">";
        if ($folderId) $strRet .= $folder['info'];
        $strRet .= "</textarea><br>";
        $strRet .= "<br>";
        /*$strRet .= "<textarea name=\"endinfo\" placeholder=\"Záverečné informácie o zložke\">";
        if ($folderId) $strRet .= $folder['endinfo'];
        $strRet .= "</textarea><br>";
        $strRet .= "<br>";
        $strRet .= "<div id=\"referencesAdd\">";
        $strRet .= "Použité zdroje <Br>";
        if ($folderId) {
            while ($ref = mysql_fetch_array($folderReferences)) {
                $strRet .= "<input type=\"text\" name=\"references[]\" value=\"{$ref['reference']}\"><br>";   
            }
        }
        else $strRet .= "<input type=\"text\" name=\"references[]\" placeholder=\"použité zdroje\">";
        $strRet .= "<span onclick=\"addRef()\" class=\"addRef\">+</span><br>";
        $strRet .= "</div>"; // referencesAdd */
        $strRet .= "<br>";
        $strRet .= "<input type=\"submit\" class=\"btn btn-success\" value=\"Pridať\">";
        $strRet .= "</form>";
        $strRet .= "</div>";
        return $strRet;
    }
    
    public function getType() {
        $strRet = "";
        if ($this->type == 'noteAdd') {
            if (isset($_GET['noteId']))
                $noteId = $_GET['noteId'];
            else 
                $noteId = null;
            $strRet .= $this->noteAdd(false, $noteId);
        } else if ($this->type == 'folderAdd') {
            if (isset($_GET['folderId']))
                $folderId = $_GET['folderId'];
            else 
                $folderId = null;
            $strRet .= $this->folderAdd($folderId);
        } else if ($this->type == 'noteVisualAdd') {
          if (isset($_GET['noteId']))
                $noteId = $_GET['noteId'];
            else 
                $noteId = null;
            $strRet .= $this->noteAdd(true, $noteId);
        } else if ($this->type == 'topicAdd') {
            $strRet .= $this->topicAdd();
        } else if ($this->type == 'replyTopic') {
            $strRet .= $this->replyTopic($_GET['topic'], $this->user['id']);
        }
        return $strRet;
    }
}
