<?php

class cHelp extends cPage {
    
    public function __construct() {
        parent::__construct();    
}

    public function getContent() {
        $strRet = "";
        $strRet .= "<div id=\"helpContent\">" 
                . "<h1>Vitaj na stránke University SYNC</h1>"
                . "<p>"
                . "Stránka sa momentálne nachádza v štádiu intenzívnej výstavby.<br>"
                . "Začiatok výstavby: 1.4.2014.<br>"
                . "<br>"
                . " Nachádzaš sa v priestoroch úplne nového portálu University SYNC. Portál sa bude primárne zameriavať na výrobu a prevádzku stránky, pre skupinu užívateľov s podobným zameraním. Po vytvorení užívateľského konta, si budeš môcť vytvoriť svoju vlastnú skupinu a pozývať do nej členov svojej katedry, triedy, fakulty alebo záujmovej skupiny. Budeš môcť takisto vstúpiť do hocijakej inej skupiny.<br>"
                . "<br>"
                . " Nenapísal som ani 10% z toho, čo chystáme. ;)<br>"
                . "Ak tu náhodou zablúdite, tak sa nezabudnite zastaviť o nejaký ten mesiac."
                . "</p>"
                . "<br>"
                . "<f>2014 © MiKme development team</f>"
                . "</div>";
        
        return $strRet; 
    }
    
}