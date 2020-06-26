<?php
    setcookie('PHPSESSID', '', time() - 3600);
    $bg = "background: url('views/img/Campaign-Analytics-Banking.jfif') no-repeat center center; background-size: cover;";
    include_once('views/includes/phpmailer.php');
    include_once('views/includes/smtp.php');
    include_once('views/includes/envio.php');
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
        <a href="/?login" class="btn btn-mex btn-mex btn-login">Login</a>
        <section class="box">
            <img src="views/img/Logo_Mex.png" class="logo" alt="Logo Mex Consulting">
            <h1 class="title">A Transformação Digital de sua empresa começa com a MEX Consulting!</h1>
            <p class="sub">Somos uma empresa focada em análise do atendimento ao cliente na busca de padrões de excelência, prevenção à fraude e insights da operação que melhorem a qualidade da tratativa com o cliente. Atuamos fortemente na identificação e mapeamento de processos - RPA, gerando automações assistidas e não assistidas para otimizá-los.</P>
            <button class="btn btn-mex btn-mex" data-toggle="modal" data-target="#modalContact">Entre em contato</button>
        </section>
        <div class="modal fade" id="modalContact" tabindex="-1" role="dialog" aria-labelledby="modalContactTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="title">Quer saber mais?</h3>
                        <p>Envie-nos seu contato pelo formulário abaixo:</p>
                        <form id="mailform" name="mailform">
                            <div class="form-group">
                                <input value="wendell.maranhao@mexconsulting.com.br" name="toemail" type="text" hidden />
                            </div>
                            <div class="form-group">
                                <input value="contato@mexconsulting.com.br" name="from" type="text" hidden />
                            </div>
                            <div class="form-group">
                                <input value="Contato Site | Mex Consulting" name="subject" type="text" hidden />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="nome" id="nome" placeholder="Seu nome" required>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Seu e-mail" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Sua mensagem"></textarea>
                            </div>
                            <button type="button" id="sendemail" class="btn btn-mex btn-mex" onclick="GoSend();" data-dismiss="modal" aria-label="Fechar" >Enviar</button>
                            <div class="form-group">
                                <select class="form-control" name="sendmethod" hidden >
                                    <option value="smtp" selected >SMTP</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalOk" tabindex="-1" role="dialog" aria-labelledby="modalOkTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>Pronto!</h3>
                        <p>Em breve nossos especialistas irão entrar em contato para dar mais informações.</p>
                        <a class="btn btn-mex" data-dismiss="modal" aria-label="Fechar">OK</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalErro" tabindex="-1" role="dialog" aria-labelledby="modalErroTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>Erro!</h3>
                        <p><?= "Houve um erro enviando o email. Tente de novo mais tarde!" ?></p>
                        <a class="btn btn-mex" data-dismiss="modal" aria-label="Fechar">OK</a>
                    </div>
                </div>
            </div>
        </div>

        <table hidden id="msglog" border="1" bordercolor="#FFCC00" style="background-color:#FFFFCC" width="100%" cellpadding="3" cellspacing="3">
            <tr>
                <td>#</td>
                <td>TIME</td>
                <td>TO</td>
                <td>FROM</td>
                <td>SUBJECT</td>
                <td>MESSAGE</td>
                <td>METHOD</td>
                <td>NODE</td>
                <td>RESULT</td>
            </tr>
        </table>

    </main>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        var msgid = 1;
        function GoSend() {
            var table=document.getElementById("msglog");
            var row = table.insertRow(1);
            
            var NUMcell = row.insertCell(0);
            NUMcell.innerHTML=msgid++;
            
            var DATEcell = row.insertCell(1);
            var d = new Date();
            DATEcell.innerHTML=d.toLocaleTimeString();
            
            var TOcell = row.insertCell(2);
            TOcell.innerHTML=document.mailform.toemail.value;
            
            var FROMcell = row.insertCell(3);
            FROMcell.innerHTML=document.mailform.from.value;
            
            var SUBJECTcell = row.insertCell(4);
            SUBJECTcell.innerHTML=document.mailform.subject.value;
            
            var MESSAGEcell = row.insertCell(5);
            MESSAGEcell.innerHTML=document.mailform.message.value;
            
            var METHODcell = row.insertCell(6);
            METHODcell.innerHTML=document.mailform.sendmethod.value;
            
            var NODEcell = row.insertCell(7);
            
            var RESULTcell = row.insertCell(8);
            RESULTcell.innerHTML="<img height=\"24\" src=\"data:image/gif;base64,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\">";

            var postdata= "sendemail=1&toemail="+document.mailform.toemail.value;
                postdata+="&from="+document.mailform.from.value;
                postdata+="&subject="+document.mailform.subject.value;
                postdata+="&sendmethod="+document.mailform.sendmethod.value;
                postdata+="&nome="+document.mailform.nome.value;
                postdata+="&email="+document.mailform.email.value;
                postdata+="&message="+encodeURIComponent(document.mailform.message.value).replace("%20", "+");
            var url="<?=$_SERVER['PHP_SELF']; ?>";
            var request=new XMLHttpRequest();
            request.open("POST",url,true);
            request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            request.overrideMimeType("text/plain");
            request.onreadystatechange=function() { 
                if ( request.readyState==4 ) {
                    NODEcell.innerHTML=request.getResponseHeader("X-Node");
                    if ( request.responseText == "OK" || request.responseText == "FAIL" ) {
                        RESULTcell.innerHTML=request.responseText;
                    } else {
                        if ( request.status == 0 ) {
                            RESULTcell.innerHTML="ERR_EMPTY_RESPONSE";
                        } else {
                            RESULTcell.innerHTML="HTTP/1.1 "+request.status+" "+request.statusText+"<br /><br />"+request.responseText;
                            if(request.status == 200){
                                $('#modalOk').modal('show');
                            }else{
                                $('#modalErro').modal('show');
                            }
                        }
                    }
                }
            }
            request.send(postdata);
        }
    </script>
</body>
</html>