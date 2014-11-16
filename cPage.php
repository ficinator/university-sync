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
                $sql = "SELECT u.id, u.name, surname, email, university, info, rank, sex, f.name AS faculty"
                    . " FROM user AS u JOIN faculty AS f ON u.id_faculty=f.id WHERE u.email='".$_SESSION['login']."'";
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