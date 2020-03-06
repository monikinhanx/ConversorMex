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
                case "edit":
                    $this->editXml();
                break;
                case "csv":
                    $this->viewCsv();
                break;
                case "teste":
                    $this->viewTeste();
                break;
            }
        }

        private function viewHome(){
            include "views/home.php";
        }
        private function viewXml(){
            include "views/makeXml.php";
        }
        private function editXml(){
            include "views/editXml.php";
        }
        private function viewCsv(){
            include "views/makeCsv.php";
        }
        private function viewTeste(){
            include "views/teste.php";
        }
    }
?>