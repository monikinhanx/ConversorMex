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
        <section class="box inicial text-center my-auto">
            <img src="views/img/Logo_Mex.png" width="200" height="150" class="d-inline-block" alt="">
            <div class="container">
                <h1>Conversor de Arquivos</h1>
                <p class="lead text-muted">Conversor desenvolvido para preparar os arquivos recebidos dos cliente para upload no Extrator da CallMiner</p>
                <p>
                    <button class="btn btn-mex my-2 mr-5 text-white">Upload Áudio</button>
                    <button class="btn btn-mex my-2 text-white" onclick="uploadTexto()">Upload Texto</button>

                    <div id="retcsv"></div>
                </p>
            </div>
        </section>

        <section class="box instance hide text-center my-auto">
            <img src="views/img/Logo_Mex.png" width="200" height="150" class="d-inline-block" alt="">
            <div class="container">
                <h1>Escolha a Instância</h1>
                <!-- <p class="lead text-muted">Informe abaixo o caminho da pasta onde estão da Interações:</p> -->
                <form action="" method="">
                <div class="form-group">
                    <label for="instance">Selecione a instância:</label>
                    <select class="form-control" id="instance">
                        <option disabled>Escolha uma opção:</option>
                        <option value="">Compartilhada</option>
                        <option value="">Stefanini</option>
                        <option value="">Nubank</option>
                    </select>
                </div>
                    <button type="submit" class="btn btn-mex mb-3 text-white" onclick="selectInstance()">Upload Texto</button>
                </form>
            </div>
        </section>

        <section class="box path hide text-center my-auto">
            <img src="views/img/Logo_Mex.png" width="200" height="150" class="d-inline-block" alt="">
            <div class="container">
                <h1>Upload de Texto</h1>
                <form action="" method="">
                    <div class="form-group">
                        <label for="path" class="lead text-muted">Informe o caminho da pasta onde estão da Interações:</label>
                        <input class="form-control" type="text" name="path" id="path" placeholder="Ex.: C:\Users\user\Desktop">
                    </div>
                    <div class="form-group">
                        <label for="path" class="lead text-muted">Informe seu token:</label>
                        <input class="form-control" type="text" name="path" id="path" placeholder="Ex.: JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpX">
                    </div>
                    <button type="submit" class="btn btn-mex mb-3 text-white">Upload Texto</button>
                </form>
            </div>
        </section>

    </main>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="views/js/seehide.js"></script>
</body>
</html>