<?php
    session_start();

    include_once "models/Usuario.php";

    class UsuarioController{

        // Direcionando para a pagina correta
        public function acao($rotas){
            switch($rotas){
                case "cadastrousuario":
                    $this->cadastrarUsuario();
                break;
                case "cadastrar":
                    $this->registrarUsuario();
                break;
                case "login":
                    $this->loginUsuario();
                break;
                case "logar":
                    $this->logarUsuario();
                break;
                case "alterasenha":
                    $this->alteraSenha();
                break;
                case "novasenha":
                    $this->novaSenha();
                break;
                case "depoislogar":
                    $this->depoislogar();
                break;
                case "logout":
                    $this->deslogarUsuario();
                break;
            }
        }

        private function cadastrarUsuario(){
            include "views/cadastro.php";
        }

        // Metodo que direciona para pagina de login de usuario
        private function loginUsuario(){
            if(isset($_SESSION['usuario'])){
                switch($_SESSION['usuario']->operacao){
                    case "Mex":
                        header('Location:/?mex');
                    break;
                    case "Nubank":
                        header('Location:/?nubank');
                    break;
                    case "Stefanini":
                        header('Location:/?stefanini');
                    break;
                }
            }
            if($_POST['confsenha']){
                $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                $confsenha = $_POST['confsenha'];
                if(password_verify($confsenha,$senha)){
                    $db = new Usuario();
                    $altera = $db->alteraSenha($_SESSION['altera'],$senha);
                    if($altera){
                        $_SESSION['invalido'] = "Senha alterada com sucesso!";
                        include "views/login.php";
                    }else{
                        $_SESSION['invalido'] = "Senha não alterada! Tente de novo.";
                        include "views/novasenha.php";
                    }
                }else{
                    $_SESSION['invalido'] = "Senha digitadas não conferem!";
                    include "views/novasenha.php";
                }
            }else{
                $_SESSION = [];
                include "views/login.php";
            }
        }
        private function depoislogar(){
            include "views/afterLogin.php";
        }

        //Metodo para cadastrar usuario
        private function registrarUsuario(){
            //Pegando dados do usuario
            $nome = $_POST['nome'];
            $sobrenome = $_POST['sobrenome'];
            $operação = $_POST['operacao'];
            $email = $_POST['email'];
            // $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

            $db = new Usuario(); //instancia usuario

            $cadastro = $db->cadastrarUsuario($nome,$sobrenome,$operação,$email); //cadastra usuario no BD

            if($cadastro){
                $_SESSION['cadastrado'] = $db->recuperaUsuario($email); //Coloca dados do usuario na superglobal
                $_SESSION['invalido'] = "Usuário Cadastrado com Sucesso!";
                include "views/cadastrado.php";
            }else{
                $_SESSION['invalido'] = "Não foi possivel cadastrar o usuario! Verifique os dados e tente novamente.";
                header('Location:/?cadastrousuario');
            }
        }

        private function logarUsuario(){
            //Pegando dados do usuario
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            if($this->validaUsuario($email,$senha)){
                $db = new Usuario(); //instancia usuario
                $db->ultimoLogin($email);
                $_SESSION['usuario'] = $db->recuperaUsuario($email); //Coloca dados do usuario na superglobal
                $_SESSION['invalido'] = "";
                header('Location:/?depoislogar'); //direciona pra pagina de login
            }else{
                $_SESSION['invalido'] = "Email e/ou senha incorretos!"; //Coloca mensagem de erro na superglobal
                header('Location:/?login'); //direciona pra pagina de login
            }
        }

        private function novaSenha(){
            //Pegando dados do usuario
            $email = $_POST['email'];
            $db = new Usuario(); //instancia usuario
            $usuario = $db->recuperaUsuario($email); //Coloca dados do usuario na superglobal
            if($usuario->email == $email){
                $_SESSION['invalido'] = "";
                $_SESSION['altera'] = $email;
                include "views/novasenha.php"; //direciona pra pagina de nova senha
            }else{
                $_SESSION['invalido'] = "Email incorreto ou não cadastrado!"; //Coloca mensagem de erro na superglobal
                header('Location:/?altera'); //direciona pra pagina de login
            }
        }

        private function validaUsuario($email,$senha){
            $db = new Usuario(); //instancia usuario

            $usuario = $db->recuperaUsuario($email); //Pega dados do usuario

            return password_verify($senha,$usuario->senha) ? true : false; //Valida se a senha esta correta

        }

        private function deslogarUsuario(){
            session_gc();
            session_destroy(); //destroi sessão
            header('Location:/?'); //direciona pra pagina de login
        }

        private function alteraSenha(){
            include "views/altera.php";
        }
    }
?>