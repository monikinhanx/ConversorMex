<?php 
    // if(isset($_SESSION['user'])){
    //     header('Location:/?FakeInstagram/posts');
    // }

    $bg = "background: url('views/img/Campaign-Analytics-Banking.jfif') no-repeat center center; background-size: cover;";
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
    <main>
        <section class="box">
        <a href="/?" class=""><i class="fas fa-home"></i></a>
            <img src="views/img/Logo_Mex.png" class="logo" alt="Logo Mex Consulting">
            <h1 class="title">Cadastro de Usuário</h1>
            <p class="erro"><?php if(isset($_SESSION['invalido'])) echo $_SESSION['invalido'] ?></p>
            <form action="/?cadastrar" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Insira seu nome" required>
                </div>
                <div class="form-group">
                    <label for="sobrenome">Sobrenome:</label>
                    <input type="text" class="form-control" name="sobrenome" id="sobrenome" placeholder="Insira seu sobrenome" required>
                </div>
                <div class="form-group">
                        <label for="operacao">Escolha a operação do usuário:</label>
                        <select class="form-control" name="operacao" id="operacao" required>
                            <option selected disabled>Escolha a Operação...</option>
                            <option value="Stefanini">Stefanini</option>
                            <option value="Nubank">Nubank</option>
                            <option value="Mex">Mex</option>
                        </select>
                    </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Insira seu e-mail" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Insira sua senha" required>
                </div>
                <button type="submit" class="btn btn-mex mb-5">Cadastrar</button>
            </form>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>