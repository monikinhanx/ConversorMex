<?php
    session_start();

    class MexController{
        public function acao($rotas){
            switch($rotas){
                case "mex":
                    $this->viewMex(); //Mostra pagina inicial
                break;
                case "usuario":
                    $this->viewUsuario(); //Mostra pagina inicial
                break;
                case "clientes":
                    $this->viewClientes(); //Mostra pagina inicial
                break;
            }
        }

        private function viewMex(){
            include "views/Mex/mex.php";
        }
        private function viewUsuario(){
            include "views/Mex/usuario.php";
        }
        private function viewClientes(){
            include "views/Mex/clientes.php";
        }
    }
?>