<?php
    session_start();

    class HomeController{
        public function acao($rotas){
            switch($rotas){
                case "home":
                    $this->viewHome(); //Mostra pagina inicial
                break;
            }
        }

        private function viewHome(){
            include "views/home.php";
        }
    }
?>