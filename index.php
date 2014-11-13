<?php
    ini_set('upload-max-filesize', '10M');  /// osetrit
    error_reporting(E_ALL); 
    ini_set("display_errors", 0);
    header('Content-type: text/html; charset=utf-8');
    require 'text.php';
    include 'stdlib.php';
    require 'connect.php';
    
    session_start();
    
    if(!isset($_SESSION['language'])) {
        $_SESSION['language'] = 0;
    } else  {
        if(isset($_GET['language']) && $_GET['language'] == "sk") {
            $_SESSION['language'] = 0;
        }
        else if(isset($_GET['language']) && $_GET['language'] == "en")  {
            $_SESSION['language'] = 1;
        }  
    } 

    $site = new cSite();
    // this is a function specific to this site!
    initialise_site($site);
    
    if ($_GET['page'] == 'register') {
        $page = new cRegister();
    } else if ($_GET['page'] == 'action') {
        $page = new cAction();
    } else if ($_GET['page'] == 'home' ) {
        $page = new cHome();
    } else if ($_GET['page'] == 'groupAdd') {
        $page = new cGroupAdd();
    } else if ($_GET['page'] == 'group') {
        $page = new cGroup($_GET['id']);
    } else if ($_GET['page'] == 'help') {
        $page = new cHelp();
    }  else {
        $page = new cHome();
    }
    
    $mainBar = new cMainBar();
    
    $site->setMainBar($mainBar);
    
    $site->setPage($page);  
    $content = $page->getContent();

    $page->setContent($content);   

    $site->render();
?>
