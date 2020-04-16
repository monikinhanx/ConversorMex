<?php
    session_start();

    class MexController{
        public function acao($rotas){
            switch($rotas){
                case "mex":
                    $this->viewMex(); //Mostra pagina inicial
                break;
            }
        }

        private function viewMex(){
            include "views/Mex/mex.php";
        }
    }
?>