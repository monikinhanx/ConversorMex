<?php
    $bg = "background: url('views/img/BG.jpg') no-repeat center center; background-size: cover;";
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
    <title>Mex Consulting</title>
    <link rel="shortcut icon" href="views/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="views/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="views/css/style.css">
</head>
<body style="<?= $bg ?>">
    <main class="d-flex justify-content-end">
        <div class="dropdown">
            <a class="btn btn-menu dropdown-toggle text-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Olá, <?=$_SESSION['usuario']->nome?>!
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="/?">Home Mex</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/?login">Página Inicial</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/?logout">Logout</a>
            </div>
        </div>
        <section class="box-interno my-auto">
            <img src="views/img/Logo_Mex.png" width="200" height="150" class="d-inline-block" alt="">
            
            <div class="container">
                <h1 class="mb-5 title">Operação Boticário</h1>
                
                <form action="/?audio" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                    </div>
                    <div class="form-group">
                        <label for="path" class="sub mb-3">Selecione os arquivos XML com os metadados:</label>
                        <input class="form-control mb-3" type="file" name="path[]" multiple="multiple" id="path" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control mb-3" type="text" name="operacao" id="operacao" value="boticario" hidden>
                    </div>
                    <a href="/?stefanini" class="btn btn-mex mb-5">Voltar</a>
                    <button type="submit" class="btn btn-mex mb-5">Próximo</button>
                </form>
            </div>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>