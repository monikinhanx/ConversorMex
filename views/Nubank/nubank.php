<?php
    include_once('views/includes/top.php');

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

    <main id="nubank">
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
    
<?php include_once('views/includes/bottom.php'); ?>