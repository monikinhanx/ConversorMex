<?php
    session_start();

    include_once "models/Ingestion.php";

    class NubankController{
        public function acao($rotas){
            switch($rotas){
                case "nubank":
                    $this->viewNubank(); //Mostra pagina inicial nubank
                break;
                case "api":
                    $this->api(); //Mostra pagina inicial nubank
                break;
                case "chats":
                    $this->viewChats(); //Mostra pagina inicial
                break;
                case "path":
                    $this->viewPath(); //Mostra pagina de escolha dos arquivos
                break;
                case "metadados":
                    $this->viewMetadados(); //Mostra pagina confirmação de metadados
                break;
                case "upload":
                    $this->uploadChats(); //Mostra pagina inicial
                break;
            }
        }

        private function viewNubank(){
            $_SESSION['title'] = "Mex Consulting - Nubank";
            include "views/Nubank/nubank.php";
        }
        
        private function viewChats(){
            $_SESSION['title'] = "Mex Consulting - Nubank";
            
            $ftp_server = "104.214.56.69";
            $ftp_user_name = "Stefanini";
            $ftp_user_pass = "Stefanini@2020";

            $arquivo = fopen ("ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/monica.txt", "r");
            if (!$arquivo) {
                echo "<p>Não posso abrir o arquivo para leitura</p>";
                exit;
            }
            $texto="";
            while ($linha = fgets($arquivo,1024)) {
                if ($linha) $texto .= $linha;
            }
            echo $texto;
            fclose ($arquivo);
            die;


            $conn_id = ftp_connect($ftp_server);

            // login com nome de usuário e senha
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

            $dir = ftp_nlist($conn_id, ".");

            var_dump($dir);

            // mostra o diretório atual
            // echo ftp_pwd($conn_id); // /

            // fecha esta conexão
            ftp_close($conn_id);

            // include "views/Nubank/chats.php";
        }

        private function uploadChats(){
            set_time_limit(0);
            $path = $_SERVER['DOCUMENT_ROOT']."/views/uploadNubank/";
            // $path = $_POST['path']."/";

            $arquivos = isset($_FILES['chat']) ? $_FILES['chat'] : false;

            for ($i = 0; $i < count($arquivos['name']); $i++){ 
                $destino = $path.$arquivos['name'][$i];
                move_uploaded_file($arquivos['tmp_name'][$i], $destino);
            }
        }

        private function api(){
            unset($_SESSION['nubank']);
            $username = $_POST['login'];
            $password = $_POST['senha'];
            $StartDate = date('Y-m-d\TH:i:s', strtotime($_POST['datainicio']));
            $EndDate = date('Y-m-d\TH:i:s', strtotime($_POST['datafim']));
            $page = 1;
            $db = new Ingestion();

            if($EndDate < $StartDate){
                $_SESSION['nubank'] = "data";
                $db->relatorios($username,$StartDate,$EndDate,"Data Inicial é maior do que Data Final.");
                echo "<script>window.location.href = '/?nubank';</script>";
                die;
            }

            $jwt = $this->getJWT($username, $password);

            if($jwt['header'][0] != "HTTP/1.1 200 OK"){
                $_SESSION['nubank'] = "login";
                $db->relatorios($username,$StartDate,$EndDate,"Login e/ou senha incorretos.");
                echo "<script>window.location.href = '/?nubank';</script>";
                die;
            }

            $categories = $this->getCategories($jwt['body']);
            $nomeCategorias = [];

            foreach($categories['body'] as $cat){
                if(($cat['SectionName'] == "TOM DE VOZ _original") || ($cat['SectionName'] == "TOM DE VOZ")){
                    array_push($nomeCategorias,$cat['BucketFullname']);
                }
            }

            if($EndDate == $StartDate){
                $EndDate = date('Y-m-d\TH:i:s', strtotime("+1 day",strtotime($EndDate)));
            }

            $search = $this->getSearch($jwt['body'],$StartDate,$EndDate,$page);

            if(empty($search['body'])){
                $_SESSION['nubank'] = "resultado";
                $db->relatorios($username,$StartDate,$EndDate,"Sua busca não teve resultados.");
                echo "<script>window.location.href = '/?nubank';</script>";
                die;
            }

            header('Cache-Control: max-age=0');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="export_CM.csv"');
            $output = fopen('php://output', 'w+');

            fputcsv($output, array("Eureka ID","source_id","agente","actor_squad","actor_affiliation","activity_type","Grade - Score TOM DE VOZ","Categories","Rating"));

            while(!empty($search['body'])){
                foreach($search['body'] as $contato){
                    $catHit = [];
                    $weight = 0;
                    
                    foreach($contato['Categories'] as $cat){
                        array_push($catHit,$cat['BucketFullName']);
                    }

                    foreach($contato['Scores'] as $score){
                        if($score['ScoreId'] == 61){
                            $weight = $score['Weight'];
                        }
                    }
            
                    foreach($nomeCategorias as $cat){
                        if(in_array($cat, $catHit)){
                            fputcsv($output, array($contato['Contact']['Id'],$contato['Others']['UDF_text_17'],$contato['Attributes']['Agent'],$contato['Attributes']['UDF_text_04'],$contato['Attributes']['UDF_text_13'],$contato['Attributes']['UDF_text_02'],$weight,$cat,"Hit"));
                        }else{
                            fputcsv($output, array($contato['Contact']['Id'],$contato['Others']['UDF_text_17'],$contato['Attributes']['Agent'],$contato['Attributes']['UDF_text_04'],$contato['Attributes']['UDF_text_13'],$contato['Attributes']['UDF_text_02'],$weight,$cat,"Miss"));
                        }
                    }
                }
                $page++;
                $search = $this->getSearch($jwt['body'],$StartDate,$EndDate,$page);
            }
            fclose( $output );
            $db->relatorios($username,$StartDate,$EndDate,"Relatório Gerado com Sucesso");
            // $this->generateAndDownloadFileCSV($resultado);
        }

        private function callAPI($method, $header, $string, $endpoint){
            $context = stream_context_create(array(
                'http' => array(
                    'method' => $method,
                    'header' => $header,
                    'content' => $string                            
                )
            ));

            $contents = file_get_contents($endpoint, null, $context);            
            $body = json_decode($contents,true);
            
            $resposta = ["header" => $http_response_header, "body" => $body];
            
            return $resposta;
        }

        private function getJWT($username, $password){
            $method = 'POST';
            $header = "Content-type: application/json; charset=utf-8\r\n";
            // $header = ["Content-type" => "application/json; charset=utf-8"];
            $string = "{
                \"Username\": \"{$username}\",
                \"Password\": \"{$password}\",
                \"ApiKey\": \"nubank\"
            }";

            $endpoint = "https://sapi.callminer.net/security/getToken";
    
            $resposta = $this->callAPI($method, $header, $string, $endpoint);
    
            return $resposta;
        }

        private function getCategories($jwt){
            $header = "Authorization: JWT ".$jwt."\r\nContent-type: application/json; charset=utf-8\r\n";    
            $endpoint = "https://feapi.callminer.net/api/v2/categories";

            $resposta = $this->callAPI('GET',$header,"",$endpoint);
    
            return $resposta;
        }

        private function getSearch($jwt,$StartDate,$EndDate,$page){
            $header = "Authorization: JWT ".$jwt."\r\nContent-type: application/json; charset=utf-8\r\n";

            $endpoint = "https://feapi.callminer.net/api/v2/export/datesearch?startDate=$StartDate&stopDate=$EndDate&page=$page&useClientCaptureDate=true";
    
            $resposta = $this->callAPI('GET',$header,"",$endpoint);
    
            return $resposta;
        }

        private function generateAndDownloadFileCSV($resultado){
            header('Cache-Control: max-age=0');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="export_CM.csv"');
            $output = fopen('php://output', 'w+');
            foreach ($resultado as $value) {
                fputcsv($output, $value);
            }
            fclose( $output );
        }

        private function viewPath(){
            include "views/Nubank/path.php";
        }

        private function viewMetadados(){
            set_time_limit(0);
            $path = $_SERVER['DOCUMENT_ROOT']."/views/uploadNubank/";
            // $path = $_POST['path']."/";

            $arquivos = isset($_FILES['path']) ? $_FILES['path'] : false;

            for ($i = 0; $i < count($arquivos['name']); $i++){ 
                $destino = $path.$arquivos['name'][$i];
                move_uploaded_file($arquivos['tmp_name'][$i], $destino);
            }

            $diretorio = dir($path);
            $resultado = [];
            $erro = [];

            while($arquivo = $diretorio -> read()){
                // if(strlen($arquivo) > 3 && $arquivo != "upload.txt"){
                if(strlen($arquivo) > 3){
                    // echo "<a href='".$path.$arquivo."'>".$arquivo."</a><br />";
                    $delimitador = ',';
                    $cerca = '"';
                    $f = fopen($path.$arquivo, 'r');
                    if ($f) {
                        $cabecalho = fgetcsv($f, 0, $delimitador, $cerca);
                        $string = "";
                        
                        while (!feof($f)){
                            $linha = fgetcsv($f, 0, $delimitador, $cerca);

                            if (!$linha) {
                                continue;
                            }
                            if(count($cabecalho) == count($linha)){
                                $registro = array_combine($cabecalho, $linha);
                                $data = $this->before("T", $registro['PostDateTime'])." ".$this->between("T", ".", $registro['PostDateTime']);
                                if(empty($string)){
                                    $string = "{
        \"Metadata\": [
            {
                \"Key\": \"ClientCaptureDate\",
                \"Value\": \"$data\"
            },
            {
                \"Key\": \"CallDate\",
                \"Value\": \"$data\"
            },
            {
                \"Key\": \"Agent\",
                \"Value\": \"{$registro['agent']}\"
            },
            {
                \"Key\": \"ExitStatus\",
                \"Value\": \"{$registro['status']}\"
            },
            {
                \"Key\": \"ClientID\",
                \"Value\": \"{$registro['source_id']}\"
            },
            {
                \"Key\": \"UDF_text_01\",
                \"Value\": \"{$registro['subject_id']}\"
            },
            {
                \"Key\": \"UDF_text_02\",
                \"Value\": \"{$registro['activity_type']}\"
            },
            {
                \"Key\": \"UDF_text_03\",
                \"Value\": \"{$registro['actor_level']}\"
            },
            {
                \"Key\": \"UDF_text_04\",
                \"Value\": \"{$registro['actor_squad']}\"
            },
            {
                \"Key\": \"UDF_text_05\",
                \"Value\": \"{$registro['badges_interpretation']}\"
            },
            {
                \"Key\": \"UDF_text_06\",
                \"Value\": \"{$registro['selected_job_squad']}\"
            },
            {
                \"Key\": \"UDF_text_07\",
                \"Value\": \"{$registro['selected_reason']}\"
            },
            {
                \"Key\": \"UDF_text_08\",
                \"Value\": \"{$registro['actor_maturity']}\"
            },
            {
                \"Key\": \"UDF_text_09\",
                \"Value\": \"{$registro['skip']}\"
            },
            {
                \"Key\": \"UDF_text_10\",
                \"Value\": \"{$registro['account__id']}\"
            },
            {
                \"Key\": \"UDF_text_11\",
                \"Value\": \"{$registro['customer_tag_formal']}\"
            },
            {
                \"Key\": \"UDF_text_12\",
                \"Value\": \"{$registro['customer_tag_deficiente']}\"
            },
            {
                \"Key\": \"UDF_text_13\",
                \"Value\": \"{$registro['actor_affiliation']}\"
            },
            {
                \"Key\": \"UDF_text_14\",
                \"Value\": \"{$registro['csat_rating']}\"
            },
            {
                \"Key\": \"UDF_text_15\",
                \"Value\": \"{$registro['local_start_date']}\"
            },
            {
                \"Key\": \"UDF_text_16\",
                \"Value\": \"{$registro['local_todo_date']}\"
            },
            {
                \"Key\": \"UDF_Int_01\",
                \"Value\": \"{$registro['net_time_spent']}\"
            }
            ],
        \"MediaType\": \"Chat\",
        \"ClientCaptureDate\": \"{$registro['PostDateTime']}\",
        \"SourceId\": \"TeraVoz\",
        \"CorrelationId\": \"{$registro['source_id']}\",
        \"Transcript\": [";
                            }
                            $texto = addslashes($registro['Text']);
                            $chat = "
                {
                    \"Speaker\": {$registro['Speaker']},
                    \"Text\": \"{$texto}\",
                    \"PostDateTime\": \"{$registro['PostDateTime']}\",
                    \"TextInformation\": \"{$registro['TextInformation']}\"
                },";
                            $string .= $chat;
                        }else{
                            array_push($erro,$arquivo);
                        }
                    }
                        $endData = "
            ]
        }";
                    if(!empty($string)){
                        $string = $this->before_last(",", $string);
                        $string .= $endData;
                        array_push($resultado,$string);
                    }
                        fclose($f);
                    }
                    unlink($path.$arquivo);
                }
            }
            $diretorio -> close();
            $_SESSION["resultado"] = $resultado;
            $_SESSION["qtdRes"] = count($resultado);
            $_SESSION["qtdErro"] = count($erro);
            $_SESSION["erro"] = $erro;
            include "views/Nubank/metadados.php";
        }

        private function viewApi(){
            set_time_limit(0);

            $string = json_decode($_POST['metadados']);
            $erro = [];
            $resultado = [];


            foreach($string as $chat){
                $context = stream_context_create(array(
                        'http' => array(
                            'method' => 'POST',                    
                            'header' => "Authorization: JWT eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpLmNhbGxtaW5lci5uZXQiLCJhdWQiOiJodHRwOi8vYXBpLmNhbGxtaW5lci5uZXQiLCJuYmYiOjE1ODQzNzA0MTksImV4cCI6MTY0NzQ0MjQxOSwidW5pcXVlX25hbWUiOiJNRVgtTnViYW5rX0luZ2VzdGlvblVzZXJfY2FhMGY3ZTMxYzkxNDI3OTg2N2VhMDVjMTg5MWU0OTdAY2FsbG1pbmVyLmNvbSIsImVtYWlsIjoiTUVYLU51YmFua19Jbmdlc3Rpb25Vc2VyX2NhYTBmN2UzMWM5MTQyNzk4NjdlYTA1YzE4OTFlNDk3QGNhbGxtaW5lci5jb20iLCJhY3RvcnQiOiJOdWJhbmsiLCJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9sb2NhbGl0eSI6ImVuLVVTIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9leHBpcmF0aW9uIjoiMDMvMTYvMjAyMiAxNDo1MzozOSJ9.T1n90l-ncDluwmC2NwIIjIPRNuyz8z9ab0hXsYsr3dw\r\n"."Content-type: application/json; charset=utf-8\r\n",
                            'content' => $chat                            
                        )
                    )
                );
        
                $contents = file_get_contents("https://ingestion.callminer.net/api/transcript", null, $context);            
                $resposta = json_decode($contents);

                if(is_null($resposta)){
                    array_push($erro, [$resposta,$chat]);
                }else{
                    array_push($resultado, [$resposta,$chat]);
                }
            }

            $_SESSION["erro"] = $erro;
            $_SESSION["resultado"] = $resultado;
            $_SESSION["qtdRes"] = count($resultado);
            $_SESSION["qtdErro"] = count($erro);

            include "views/Nubank/api.php";
        }
    }
?>