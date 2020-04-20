<?php
    $rotas = key($_GET)?key($_GET):"home";

    switch($rotas){
        case "home":
            include "controllers/HomeController.php";
            $controller = new HomeController();
            $controller->acao($rotas);
        break;
        case "cadastrousuario":
            include "controllers/UsuarioController.php";
            $controller = new UsuarioController();
            $controller->acao($rotas);
        break;
        case "cadastrar":
            include "controllers/UsuarioController.php";
            $controller = new UsuarioController();
            $controller->acao($rotas);
        break;
        case "login":
            include "controllers/UsuarioController.php";
            $controller = new UsuarioController();
            $controller->acao($rotas);
        break;
        case "logar":
            include "controllers/UsuarioController.php";
            $controller = new UsuarioController();
            $controller->acao($rotas);
        break;
        case "logout":
            include "controllers/UsuarioController.php";
            $controller = new UsuarioController();
            $controller->acao($rotas);
        break;
        case "alterasenha":
            include "controllers/UsuarioController.php";
            $controller = new UsuarioController();
            $controller->acao($rotas);
        break;
        case "novasenha":
            include "controllers/UsuarioController.php";
            $controller = new UsuarioController();
            $controller->acao($rotas);
        break;
        case "depoislogar":
            include "controllers/UsuarioController.php";
            $controller = new UsuarioController();
            $controller->acao($rotas);
        break;
        case "nubank":
            include "controllers/NubankController.php";
            $controller = new NubankController();
            $controller->acao($rotas);
        break;
        case "path":
            include "controllers/NubankController.php";
            $controller = new NubankController();
            $controller->acao($rotas);
        break;
        case "metadados":
            include "controllers/NubankController.php";
            $controller = new NubankController();
            $controller->acao($rotas);
        break;
        case "api":
            include "controllers/NubankController.php";
            $controller = new NubankController();
            $controller->acao($rotas);
        break;
        case "stefanini":
            include "controllers/StefaniniController.php";
            $controller = new StefaniniController();
            $controller->acao($rotas);
        break;
        case "operacao":
            include "controllers/StefaniniController.php";
            $controller = new StefaniniController();
            $controller->acao($rotas);
        break;
        case "selecionaoperacao":
            include "controllers/StefaniniController.php";
            $controller = new StefaniniController();
            $controller->acao($rotas);
        break;
        case "nestle":
            include "controllers/StefaniniController.php";
            $controller = new StefaniniController();
            $controller->acao($rotas);
        break;
        case "boticario":
            include "controllers/StefaniniController.php";
            $controller = new StefaniniController();
            $controller->acao($rotas);
        break;
        case "audio":
            include "controllers/StefaniniController.php";
            $controller = new StefaniniController();
            $controller->acao($rotas);
        break;
        case "amostra":
            include "controllers/StefaniniController.php";
            $controller = new StefaniniController();
            $controller->acao($rotas);
        break;
        case "cm":
            include "controllers/StefaniniController.php";
            $controller = new StefaniniController();
            $controller->acao($rotas);
        break;
        case "mex":
            include "controllers/MexController.php";
            $controller = new MexController();
            $controller->acao($rotas);
        break;
        case "clientes":
            include "controllers/MexController.php";
            $controller = new MexController();
            $controller->acao($rotas);
        break;
        case "usuario":
            include "controllers/MexController.php";
            $controller = new MexController();
            $controller->acao($rotas);
        break;
    }
    ?>