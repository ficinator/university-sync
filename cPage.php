<?php
    abstract class cPage {
        private $content;
        protected $user;

        public function __construct() {
            if (!isset ($_SESSION['login'])) {
                if ($_GET['page'] == 'register' || $_GET['page'] == 'help' || $_GET['page'] == 'home') {
                    // povolene stranky bez prihlasenia sa
                } else {
                    Header ("Location: index.php?page=home");
                }
            } else {
                $this->user = $this->getUser();
            }
        }
        
        public function getUser() {
            if (isset($_SESSION['login'])) {
                $sql = "SELECT * FROM user WHERE user.email='".$_SESSION['login']."'";
                $result = mysql_query($sql);
                $user = mysql_fetch_array($result);
                return $user;
            }
            return null;
        }

        public function __destruct() {
            // clean up here
        }
            
        public function setContent($content) {
            $this->content = $content;
        }
        
        public function render() {
            echo $this->content;
        }

        abstract protected function getContent();

        
    }
?>