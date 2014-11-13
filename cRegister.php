<?php

class cRegister extends cPage {
    public function __construct() {
        parent::__construct();
    }
    
    public function getContent() {
        require 'text.php';
        $strRet = "";
        if (isset ($_GET['result'])) {
            $strRet .= ($_GET['result'] == 'regOk') ? "Registrácia prebehla úspešne" : "Registrácia sa nepodarila";
            header("refresh:2;url=http://www.universitysync.sk/?page=register");
        } else {
        $strRet .= "<div id=\"reg-user\">";
        $strRet .= "<h2>Registrácia nového užívateľa</h2>";
        $strRet .= "<form method=\"post\" action=\"?page=action&type=register\" onsubmit=\"return checkLogin()\">"
                . "<ul>"
                . "<li><input type=\"text\" name=\"name\" class=\"regText\" id=\"reg-name\" maxlength=\"18\" placeholder=\"Meno\"></li>"
                . "<li><input type=\"text\" name=\"surname\" class=\"regText\" id=\"reg-surname\"  maxlength=\"25\" placeholder=\"Priezvisko\"></li>"
                . "<li class=\"sex\"><input type=\"radio\" id=\"sex-female\" name=\"sex\" value=\"female\" class=\"regGender\"><label for=\"sex-female\">Žena</label><input type=\"radio\" id=\"sex-male\" name=\"sex\" value=\"male\" class=\"regGender\"><label for=\"sex-male\">Muž</label></li>"
                . "<li><input type=\"text\" id=\"checkEmail\" name=\"email\" class=\"regText regEmail\" placeholder=\"E-mail\"></li>"
                . "<li><input type=\"password\" name=\"password\" id=\"txtNewPassword\" class=\"regText txtConfirmPassword mainPwd\" maxlength=\"18\" placeholder=\"Heslo\"><label id=\"checkPasswordLength\">min. 6 znakov</label></li>"
                . "<li><input type=\"password\" name=\"password1\" id=\"txtConfirmPassword\" class=\"regText txtConfirmPassword\" maxlength=\"18\" placeholder=\"Zvopakuj to\"><label id=\"checkPasswordMatch\"></label></li>"; 
        $strRet .= '<li>' . cGroupAdd::setUniversity(null) . '</li>';
        $strRet .= '<li><div id="faculty"></div></li>';
       // $strRet .= "<li><label>Napíš niečo o sebe:<textarea name=\"info\" placeholder=\"Pár viet o tebe a tvojich záujmoch. Napíš niečo, čo ťa najlepšie vystihuje.\" rows=\"3\" cols=\"50\"></textarea></li><br>"; 
        $strRet .= "<li class=\"submit\"><input type=\"submit\" value=\"Registrovať\"></li>";
        $strRet .= "</ul>"; 
        $strRet .= "</form>"; 
        $strRet .= "</div>";
        }
        return $strRet;
    }
}
