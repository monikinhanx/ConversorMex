<?php
    $rotas = key($_GET)?key($_GET):"home";

    switch($rotas){
        case "home":
            include "controllers/MakeController.php";
            $controller = new MakeController();
            $controller->acao($rotas);
        break;
        case "xml":
            include "controllers/MakeController.php";
            $controller = new MakeController();
            $controller->acao($rotas);
        break;
        case "edit":
            include "controllers/MakeController.php";
            $controller = new MakeController();
            $controller->acao($rotas);
        break;
        case "csv":
            include "controllers/MakeController.php";
            $controller = new MakeController();
            $controller->acao($rotas);
        break;
        case "teste":
            include "controllers/MakeController.php";
            $controller = new MakeController();
            $controller->acao($rotas);
        break;
    }
?>