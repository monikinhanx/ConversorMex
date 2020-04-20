<?php
    session_start();

    class MexController{
        public function acao($rotas){
            switch($rotas){
                case "mex":
                    $this->viewMex(); //Mostra pagina inicial
                break;
                case "usuarios":
                    $this->viewUsuarios(); //Mostra pagina inicial
                break;
                case "clientes":
                    $this->viewClientes(); //Mostra pagina inicial
                break;
                case "relatorios":
                    $this->viewRelatorios(); //Mostra pagina inicial
                break;
            }
        }

        private function viewMex(){
            include "views/Mex/mex.php";
        }
        private function viewUsuarios(){
            include "views/Mex/usuarios.php";
        }
        private function viewClientes(){
            include "views/Mex/clientes.php";
        }
        private function viewRelatorios(){
            include "views/Mex/relatorios.php";
        }
    }
?>