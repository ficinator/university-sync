<?php
  /*
  Trieda reprezentujuca nastavenia a informacie o skupine
  Zahrna editovanie udajov adminom skupiny
  Rozsiruje triedu cGroup
  */
  class cGroupSettings extends cGroup {
      private $edit;    // true or false
      public function __construct($id) {
          parent::__construct($id);
          $this->edit = false;
          if ($_GET['e'] == 'true' && $this->adminOfGroup)
              $this->edit = true;              
      }
      
      public function settingsTableHeader() {
          $strRet = "";
          $strRet .= "<div id=\"settingsTableHeader\">";
          
          for ($i=0; $i<4; $i++) {     // cyklus pre hornyBar tabulky
              $strRet .= "<div class=\"tableHeaderItem\" onclick=\"window.location='";
              switch($i) {
                  case 0:
                      $strRet .= "?page=group&id={$this->group['id']}&show=settings&p=main'\">";
                      $strRet .= "Hlavné informácie";
                      break;
                  case 1:
                      $strRet .= "?page=group&id={$this->group['id']}&show=settings&p=members'\">";
                      $strRet .= "Členovia skupiny";
                      break;
                  case 2:
                      $strRet .= "?page=group&id={$this->group['id']}&show=settings&p=info'\">";
                      $strRet .= "Informácie o skupine";
                      break;
                  case 3:
                      $strRet .= "?page=group&id={$this->group['id']}&show=settings&p=stats'\">";
                      $strRet .= "Štatistiky skupiny";
                      break;      
              }
              $strRet .= "</div>";    //tableHeaderItem
          }
          $strRet .= "</div>";      // settingsTableHeader
          return $strRet;
      }
      
      public function getInfo() {
          $strRet = "";
          $strRet .= "<div id=\"settingsInfo\">";
          $strRet .= $this->getEditBtn();
          if ($this->edit) {
              $strRet .= "<div id=\"settingsInfoForm\">";
              $strRet .= "<form action=\"?page=action&type=editGroupInfo&groupId={$this->group['id']}&userId={$this->user['id']}\" method=\"post\">";
          }
          $strRet .= "<div class=\"infoHeader\">Informácie o skupine</div>";
          if ($this->edit)
              $strRet .= cGroupAdd::setInfoGroup($this->group['info']);
          else {
              $strRet .= "<div class=\"settingsInfoGroup\">";
              $strRet .= $this->group['info'];
              $strRet .= "</div>";
          }
          if ($this->edit) {
              $strRet .= "<li><label>Inofrmácie pre uchádzačov do skupiny<label></li>";
              $strRet .= cGroupAdd::setInfoMemberGroup($this->group['member_info']);
          }
          else if ($this->group['member_info'] != null) {
              $strRet .= "<div class=\"infoHeader\">Informácie pre uchádzačov do skupiny</div>";
              $strRet .= "<div class=\"settingsInfoGroup\">";
              $strRet .= $this->group['member_info'];
              $strRet .= "</div>";
          }
          if ($this->edit) {
              $strRet .= "<input type=\"submit\" class=\"btn btn-success settingsEditBtn\" value=\"Uprav\">";
              $strRet .= "</form>";
              $strRet .= "</div>";    //settingsInfoForm
          }
          $strRet .= "</div>";    // settingsInfo
          return $strRet;
      }
      
      private function getEditBtn() {
          $strRet = "";
          if ($this->adminOfGroup && $_GET['e'] != 'true') {
              $strRet .= "<span>";
              $strRet .= "<button class=\"btn btn-default btn-xs\" title=\"upraviť\" onclick=\"window.location='{$_SERVER['REQUEST_URI']}&e=true'\">";
              $strRet .= "&#10000</button>";
              $strRet .= "</span>";
          }
          return $strRet;
      }
      
      public function getMain() {
          $strRet = "";
          $strRet .= "<div id=\"settingsMain\">";
          $strRet .= $this->getEditBtn();
          if ($this->edit) {
              $strRet .= "<div id=\"settingsMainForm\">";
              $strRet .= "<form action=\"?page=action&type=editGroupMain&groupId={$this->group['id']}&userId={$this->user['id']}\" method=\"post\">";
          }
          $strRet .= "<li>";
          $strRet .= "<label>Názov </label>";
          if ($this->edit) { 
              $strRet .= cGroupAdd::setName($this->group['name']);
          }else 
              $strRet .= "{$this->group['name']}";
          $strRet .= "</li>";
          $strRet .= "<li>";
          $strRet .= "<label>Viditeľnosť</label>";
          if ($this->edit) {
              $strRet .= "<input type=\"radio\" name=\"privacy\" value=\"0\" class=\"groupAddPrivacy\"";
              if ($this->group['public'] == '0') $strRet .= " checked";
              $strRet.= ">zatvorená skupina";
              $strRet .= "<input type=\"radio\" name=\"privacy\" value=\"1\" class=\"groupAddPrivacy\"";
              if ($this->group['public'] == '1') $strRet .= " checked";
              $strRet .= ">otvorená skupina";
          } else {
              if($this->group['public'] == '0') $strRet .= "zatvorená";
              else $strRet .= "otvorená";
              $strRet .= " skupina";  
          }
          $strRet .= "</li>";
          $strRet .= "<li><label>Univerzita (alebo vysoká škola)</label>";
          if ($this->edit) {
              $strRet .= cGroupAdd::setUniversity($this->group['university']);
          }
          else
              $strRet .= $this->group['university'];
          $strRet .= "</li>";
          if ($this->edit) {
              $strRet .= "<input type=\"submit\" class=\"btn btn-success settingsEditBtn\" value=\"Uprav\">";
              $strRet .= "</form>";
              $strRet .= "</div>";      // settingsMainForm
          }
          $sqlCategory = "SELECT * FROM `category` WHERE id_group = {$this->group['id']}";
          $resCategory = mysql_query($sqlCategory);
          $strRet .= "<li><label>Kategórie</label> ";
          $strRet .= "<select>";
          while($category = mysql_fetch_array($resCategory)) {
              $strRet .= "<option>{$category['name']}</option>";
          }
          $strRet .= "</select>";
          if ($this->edit) {
            $strRet .= "<button type=\"button\" title=\"pridať novú kategóriu\" class=\"btn btn-default btn-xs addCategoryBtn\">+</button>";
            $strRet .= "<button type=\"button\" title=\"vymazať kategóriu\" class=\"transparent btn-danger btn btn-xs delCategoryBtn\">X</button>";
          }
          $strRet .= "</li>";
          if($this->edit) {
              $strRet .= "<div id=\"delCategory\">";
              $strRet .= "<form action=\"?page=action&type=delCategory\" method=\"post\">";
              $strRet .= "<select name=\"name\" id=\"selectFileCategories\">";
              $resCategory = mysql_query($sqlCategory);
              while ($category = mysql_fetch_array($resCategory)) {
                  $strRet .= "<option value=\"{$category['name']}\">";
                  $strRet .= $category['name'];
                  $strRet .= "</option>";
              }
              $strRet .= "</select><br>";  
              $strRet .= "<input type=\"submit\" value=\"zmaž\" class=\"btn btn-danger btn-default btn-xs\">";
              $strRet .= "</form>";
              $strRet .= "</div>";  // delCategory
          }
          $strRet .= "<div id=\"addCategory\">";
          if ($this->adminOfGroup) $strRet .= "<form action=\"?page=action&type=addCategory&groupId={$this->group['id']}&userId={$this->user['id']}\" method=\"post\">";
          else $strRet .= "<form action=\"?page=action&type=addCategoryReq&groupId={$this->group['id']}&userId={$this->user['id']}\" method=\"post\">";
          $strRet .= "<input type=\"text\" name=\"name\" required><br>";
          $strRet .= "Spadá do kategórie: <select name=\"parent\" id=\"selectFileCategories\">";
              $resCategory = mysql_query($sqlCategory);
              $strRet .= "<option value=\"0\"></option>";
              while ($category = mysql_fetch_array($resCategory)) {
                  $strRet .= "<option value=\"{$category['name']}\">";
                  $strRet .= $category['name'];
                  $strRet .= "</option>";
              }
              $strRet .= "</select><br>";  
          $strRet .= "<input type=\"submit\" value=\"pridaj\" class=\"btn btn-success btn-default btn-xs\">";
          $strRet .= "</form>";
          $strRet .= "</div>";  // addCategory
          if ($this->adminOfGroup) {
              $strRet .= "<span class=\"changePhoto\">";
              $strRet .= "<form action=\"?page=action&type=uploadGroupPhoto&groupId={$this->group['id']}\" method=\"post\" enctype=\"multipart/form-data\">";
              $strRet .= "<input type=\"file\" name=\"photo\">";
              $strRet .= "<button type=\"submit\" class=\"btn btn-success btn-sm\">Nahraj</button>";
              $strRet .= "</form>";  
              $strRet .= "</span>";   //changePhoto
          }
          $strRet .= "</div>";  // settingsMain
          return $strRet;
      }
      
      public function getMembers() {
          $strRet = "";
          $strRet .= "<div id=\"settingsMembers\">";
          if ($this->memberOfGroup) {
            $strRet .= "<button type=\"button\" class=\"btn-xs btn btn-danger\" onclick=\"conf('?page=action&type=leaveGroup&userId={$this->user['id']}&groupId={$this->group['id']}')\">";
            $strRet .= "Odísť zo skupiny</button><Br>";   
            }
          $sql = "SELECT * FROM `member` WHERE id_group = {$this->group['id']} ORDER BY admin DESC";
          $result = mysql_query($sql);
          $countMembers = mysql_num_rows($result);
          $sqlA = "SELECT * FROM `member` WHERE id_group = {$this->group['id']} AND admin = 1 ORDER BY admin DESC";
          $countAdmins = mysql_num_rows(mysql_query($sqlA));
          $strRet .= "<li>";
          $strRet .= "<label>Počet všetkých členov: </label> {$countMembers}";
          $strRet .= "</li>";
          $strRet .= "<li>";
          $strRet .= "<label>Počet adminov skupiny: </label> {$countAdmins}";
          $strRet .= "</li>";
          $strRet .= "<div id=\"membersDiv\">";
          while ($member = mysql_fetch_array($result)) {
              $sql = "SELECT * FROM `user` WHERE id = {$member['id_user']}";
              $tmp = mysql_query($sql);
              $user = mysql_fetch_array($tmp);
              if ($this->memberOfGroup || $this->adminOfGroup || $member['admin'] == 1) {
                  $strRet .= "<li>";   
                  $strRet .= "<a href=\"?page=home&userId={$user['id']}\">";
                  $strRet .= "<img src=\"./users/{$user['id']}/";
                  if ($member['admin'] ==  1) $strRet .= "userPhoto.jpg";
                  else $strRet .= "userPhoto.jpg";
                  $strRet .= "\" class=\"figure\">".$user['name']." ".$user['surname']."</a>";
                  if ($this->adminOfGroup && $this->user['id'] != $user['id']) {
                      $strRet .= "<div id=\"membersButtons\">";
                      $strRet .= "<button type=\"button\" class=\"btn-xs btn btn-default\" onclick=\"conf('?page=action&type=kickMember&memberId={$member['id']}')\">";
                      $strRet .= "k</button>";
                      $strRet .= "<button type=\"button\" class=\"btn-xs btn btn-default\" onclick=\"conf('?page=action&type=makeAdmin&memberId={$member['id']}')\">";
                      $strRet .= "A</button>";
                      $strRet .= "</div>";
                  }
                  $strRet .= "</li>";
              }
          }
          $strRet .= "</div>";    // membersDiv
          $strRet .= "</div>";    // settingsMembers
          return $strRet;
      }
      
      public function getStats() {
          $strRet = "";
          $sql = "SELECT id FROM `note` WHERE id_group = {$this->group['id']} AND visual = '0'";
          $countNote = mysql_num_rows(mysql_query($sql));
          $sql = "SELECT id FROM `note` WHERE id_group = {$this->group['id']} AND visual = '1'";
          $countArticle = mysql_num_rows(mysql_query($sql));
          $sql = "SELECT id FROM `topic` WHERE id_group = {$this->group['id']}";
          $countForum = mysql_num_rows(mysql_query($sql));
          $sql = "SELECT id FROM `file` WHERE id_group = {$this->group['id']}";
          $countFiles = mysql_num_rows(mysql_query($sql));
          $sql = "SELECT id FROM `member` WHERE id_group = {$this->group['id']}";
          $countMembers = mysql_num_rows(mysql_query($sql));
          $sql = "SELECT * "
                  ."FROM ( "
                  ."    SELECT date"
                  ."    FROM `news` "
                  ."    UNION ALL " 
                  ."    SELECT date"
                  ."    FROM `topic` "
                  ."    UNION ALL "
                  ."    SELECT date FROM `file`"
                  ."    UNION ALL "
                  ."    SELECT date FROM `note`"
                  ."    UNION ALL "
                  ."    SELECT date FROM `reply`"
                  ."    UNION ALL "
                  ."    SELECT date FROM `comment`"
                  ."    UNION ALL "
                  ."    SELECT date FROM `folder`"
                  ."    UNION ALL "
                  ."    SELECT date FROM `category`"
                  .")s "
                  ."WHERE id_group =  {$groupId} "
                  ."ORDER BY date DESC";
          $strRet .= "<div id=\"settingsStats\">";
          $strRet .= "<li><label>Počet poznámok</label> {$countArticle}</li>";
          $strRet .= "<li><label>Počet tém vo fóre</label> {$countForum}</li>";
          $strRet .= "<li><label>Počet súborov</label> {$countFiles}</li>";
          $strRet .= "<li><label>Počet užívateľov</label> {$countMembers}</li>";
          
          $strRet .= "</div>";
          return $strRet;
      }
      
      public function settingsTableContent() {
          $strRet = "";
          $strRet .= "<div id=\"settingsTableContent\">";
          if (!$this->memberOfGroup && !isset($_GET['p'])) $strRet .= $this->getInfo();
          else if($_GET['p'] == "info") $strRet .= $this->getInfo();
          else if($_GET['p'] == "members") $strRet .= $this->getMembers();
          else if($_GET['p'] == "stats") $strRet .= $this->getStats();
          else $strRet .= $this->getMain();
          $strRet .= "</div>";    // settingsTableContent
          return $strRet;
      }

      /* Abstract function */
      public function getContent() {
          $strRet = "";
          $strRet .= "<div id=\"groupSettings\">";
          $strRet .= "<div id=\"settingsTable\">";
          $strRet .= $this->settingsTableHeader();
          $strRet .= $this->settingsTableContent();
          $strRet .= "</div>";      // settingsTable
          $strRet .= "</div>";      // groupSettings
          return $strRet;
      }
  }
?>