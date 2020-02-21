<?php
    class MakeController{
        public function acao($rotas){
            switch($rotas){
                case "home":
                    $this->viewHome();
                break;
                case "xml":
                    $this->viewXml();
                break;
                case "csv":
                    $this->viewCsv();
                break;
            }
        }

        private function viewHome(){
            include "views/home.php";
        }
        private function viewXml(){
            include "views/makeXml.php";
        }
        private function viewCsv(){
            include "views/makeCsv.php";
        }
    }
?>