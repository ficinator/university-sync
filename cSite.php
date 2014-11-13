<?php
    class csite {
        private $headers;
        private $footers;
        private $mainBar;
        private $wrapper;
        private $page;

        public function __construct() {
            $this->headers = array();
            $this->mainBar = array();
            $this->wrapper = array();
            $this->footers = array();
        }

        public function __destruct() {
            // clean up here
        }

        public function render() {
            foreach($this->headers as $header) {
                include $header;
            }
            
            $this->mainBar->render();
            
            $this->page->render();
            
            foreach($this->footers as $footer) {
                include $footer;
            }
        }

        public function addHeader($file) {
            $this->headers[] = $file;
        }
        
        public function addWrapper($file) {
            $this->wrapper[] = $file;
        }

        public function addFooter($file) {
            $this->footers[] = $file;
        }
        
        public function setMainBar(cMainBar $mainBar) {
            $this->mainBar = $mainBar;
        }

        public function setPage(cPage $page) {
            $this->page = $page;
        }
              
        public function setMenu(cMenu $menu) {
            $this->menu = $menu;
        }
        

               
        
    } 
?>
