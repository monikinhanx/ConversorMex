<?php

    switch($_SESSION['nubank']){
        case "data":
            $msg = "Data Inicial é maior do que Data Final.";
        break;
        case "login":
            $msg = "Login e/ou senha incorretos.";
        break;
        case "resultado":
            $msg = "Sua busca não teve resultados.";
        break;
        default:
            unset($msg);
        break;

    }

    unset($_SESSION['nubank']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Somos a MEX Consulting, uma consultoria de incremento de resultados e qualidade, que atua com metodologia adaptada às necessidades dos clientes, ancorada à consagrada plataforma de speech analytics Eureka, da empresa americana CallMiner, que possibilita a captura em larga escala da voz dos consumidores (voice of customers) e dos agentes">
    <meta name="keywords" content="Incremento de Performance,Melhora de Qualidade,Auditoria de Processos,Redução de Custos,Satisfação dos Clientes,Analytics,Speech Analytics,Qualidade,Auditoria,Processo,Processos,ROI">
    <meta name="author" content="Monica Craveiro">
    <title>Mex Consulting - Nubank</title>
    <link rel="shortcut icon" href="views/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="views/img/favicon.ico" type="image/x-icon">
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/3e0edc3a21.js" crossorigin="anonymous"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="views/css/style.css">
</head>
<body>
    <main id="nubank" class="d-flex justify-content-center align-items-center">
        <section class="box-interno">
            <img src="views/img/Logo_Mex.png" width="200" height="150" class="d-inline-block" alt="Logo Mex Consulting">
            <h1 class="title">Extração de Relatório</h1>
            <p class="erro"><?=$msg?></p>
            <form action="/?api" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="login">Login CallMiner:</label>
                        <input type="email" class="form-control" name="login" id="login" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="senha">Senha CallMiner:</label>
                        <input type="password" class="form-control" name="senha" id="senha"required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="datainicio">Data Inicio:</label>
                        <input type="date" class="form-control" name="datainicio" id="datainicio" max="<?=date('Y-m-d')?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="datafim">Data Fim:</label>
                        <input type="date" class="form-control" name="datafim" id="datafim" max="<?=date('Y-m-d')?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-mex mb-5">Extrair</button>
            </form>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script>

    </script>
</body>
</html>