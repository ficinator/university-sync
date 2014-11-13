<?php
class cGroupAdd extends cPage {
    public function __construct() {
        parent::__construct();
    } 
    
    public function setName($name) {
        $strRet = "";
        $strRet .= "<input type=\"text\" class=\"groupAddName\" name=\"name\" required ";
        if($name == null) {
            $strRet .= "placeholder=\"napr. Katedra histórie\">";
        } else {
            $strRet .= "value=\"{$name}\">";
        }
        return $strRet .= "<br>";
    }
    
    public function setSurname($name) {
        $strRet = "";
        $strRet .= "<input type=\"text\" name=\"surname\" required ";
        if($name == null) {
            $strRet .= "placeholder=\"Priezvisko\">";
        } else {
            $strRet .= "value=\"{$name}\">";
        }
        return $strRet .= "<br>";
    }
    
    public function setUniversity($university) {
        $strRet = "";
        $strRet .= "<select name=\"university\" id=\"universitySelect\">";
        $strRet .= "<option";
        if ($university == null)
            $strRet .= " selected";
        $strRet .= ">Vyber univerzitu</option>";
        $sql = "SELECT * FROM `university`";
        $result = mysql_query($sql);
        while ($uni = mysql_fetch_array($result)) {
            $strRet .= "<option";
            if ($university == null) {
                $strRet .= ">";
            } else {
                if ($uni['name'] == $university) $strRet .= " selected>";
                else $strRet .= ">";
            }
            $strRet .= $uni['name'];
            $strRet .= "</option>";
        }
        $strRet .= "</select>";
        return $strRet;
    }

    
    public function setInfoGroup($info) {
        $strRet = "";
        $strRet .= "<textarea class=\"tinymce\" name=\"info\" ";
        $strRet .= ($info == null ? "placeholder=\"info\">" : ">{$info}");
        $strRet .= "</textarea><br>";
        return $strRet;
    }
    
    public function setInfoMemberGroup($info) {
        $strRet = "";
        $strRet .= "<textarea name=\"member_info\" ";
        $strRet .= ($info == null ? "placeholder=\"info pre uchádzačov\">" : ">{$info}");
        $strRet .= "</textarea>";
        return $strRet;
    }
    
    public function getContent() {
        $strRet = "";
        $strRet .= "<div id=\"groupAdd\">";
        $strRet .= "<h2>Vytvorenie novej skupiny</h2><br>";
        $strRet .= "<form action=\"?page=action&type=groupAdd&idUser={$this->user['id']}\" method=\"post\">";
        $strRet .= "<ul>";
        $strRet .= "<li><label>Názov skupiny:</label>{$this->setName(null)}</li>";
        /*$strRet .= "<li>";
        $strRet .= "<label>Druh skupiny:</label>";
        $strRet .= "<input type=\"radio\" name=\"type\" value=\"university\" checked class=\"groupAddType\">akademická";
        $strRet .= "<input type=\"radio\" name=\"type\" value=\"interest\" class=\"groupAddType\">záujmová";
        $strRet .=" </li>";*/
        $strRet .= "<li>";
        $strRet .= "<label>Prístupnosť:</label><input type=\"radio\" name=\"privacy\" value=\"0\" class=\"groupAddPrivacy\">zatvorená skupina";
        $strRet .= "<input type=\"radio\" name=\"privacy\" checked value=\"1\" class=\"groupAddPrivacy\">otvorená skupina";
        $strRet .= "<img id=\"helpHint\" title=\"Otvorená skupina - nečlenovia skupiny vidia majú prístup k poznámkam<br>Zatvorená skupina - nečlenovia skupiny nemajú prístup k obsahu skupiny\" src=\"./images/question.png\">";
        $strRet .= "</li>";
        $strRet .= "<li><label>Univerzita (alebo vysoká škola):</label>{$this->setUniversity(null)}</li>";
        $strRet .= "<li><div id=\"faculty\"></div></li>";  
        $strRet .= "<li><label>Informácie o skupine:</label></li>{$this->setInfoGroup(null)}";  
        $strRet .= "<li><label>Informácie pre záujemcov o členstvo v skupine:</label></li>";
        $strRet .= $this->setInfoMemberGroup(null);
        $strRet .= "</ul>";  
        $strRet .= "<button type=\"submit\" class=\"btn btn-lg btn-success\">Vytvoriť</button>"; 
        $strRet .= "</form>";
        $strRet .= "</div>";
        return $strRet;
    }
}
