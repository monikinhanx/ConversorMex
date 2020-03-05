<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor de Arquivos</title>
    <link rel="shortcut icon" href="views/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="views/css/style.css">
</head>
<body>
    <main>
        <section class="box text-center">
            <img src="views/img/Logo_Mex.png" width="200" height="150" class="d-inline-block" alt="">
            <div class="container">
            <h1>Conversor de Arquivos</h1>
            <p class="lead text-muted">Conversor desenvolvido para preparar os arquivos recebidos dos cliente para upload no Extrator da CallMiner</p>
            <p>
                <a href="/makeXML/xml" class="btn btn-mex my-2">Converter XML</a>
                <a onclick="gerarCSV();" class="btn btn-mex my-2">Converter CSV</a>

                <div id="retcsv"></div>
            </p>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function gerarCSV()
        {
            var el = document.getElementById("retcsv");
            var segundos = 5;
            var oReq = new XMLHttpRequest();

            //Defina como true
            oReq.open("GET", "makeCsv.php", true);

            //Função assíncrona que aguarda a resposta
            oReq.onreadystatechange = function()
            {
                if (oReq.readyState == 4) {
                    if (oReq.status == 200) {
                        el.innerHTML = oReq.responseText;
                    }

                    setTimeout(gerarCSV, segundos * 1000);
                }
            };

            //Envia a requisição, mas a resposta fica sendo aguardada em Background
            oReq.send(null);
        }
    </script>
</body>
</html>