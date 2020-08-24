<?php
    setcookie('PHPSESSID', '', time() - 3600);
    include_once('views/includes/top.php');
?>

    <main id="home">
        <section class="box">
            <img src="views/img/Logo_Mex.png" class="logo" alt="Logo Mex Consulting">
            <h1 class="title">A Transformação Digital de sua empresa começa com a MEX Consulting!</h1>
            <p class="sub">Somos uma empresa focada em análise do atendimento ao cliente na busca de padrões de excelência, prevenção à fraude e insights da operação que melhorem a qualidade da tratativa com o cliente. Atuamos fortemente na identificação e mapeamento de processos - RPA, gerando automações assistidas e não assistidas para otimizá-los.</P>
            <button class="btn btn-mex btn-mex" data-toggle="modal" data-target="#modalContact">Entre em contato</button>
        </section>
        
        <?php include_once 'views/includes/modal.php'; ?>
    </main>

<?php include_once('views/includes/bottom.php'); ?>