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
            $_SESSION['title'] = "Mex Consulting";
            include "views/home.php";
        }
    }
?>