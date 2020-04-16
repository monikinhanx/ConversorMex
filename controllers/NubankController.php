<?php
    session_start();

    class NubankController{
        public function acao($rotas){
            switch($rotas){
                case "nubank":
                    $this->viewNubank(); //Mostra pagina inicial nubank
                break;
                case "path":
                    $this->viewPath(); //Mostra pagina de escolha dos arquivos
                break;
                case "metadados":
                    $this->viewMetadados(); //Mostra pagina confirmação de metadados
                break;
                case "api":
                    $this->viewApi(); //Mostra pagina inicial
                break;
            }
        }

        private function viewNubank(){
            include "views/Nubank/nubank.php";
        }

        private function viewPath(){
            include "views/Nubank/path.php";
        }

        private function viewMetadados(){
            set_time_limit(0);
            $path = $_SERVER['DOCUMENT_ROOT']."/MexConsulting/views/uploadNubank/";
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

        protected function before_last ($texto1, $inthat){
            return substr($inthat, 0, $this->strrevpos($inthat, $texto1));
        }
    
        protected function strrevpos($instr, $needle){
            $rev_pos = strpos (strrev($instr), strrev($needle));
            if ($rev_pos===false) return false;
            else return strlen($instr) - $rev_pos - strlen($needle);
        }
    
        protected function after ($texto1, $inthat){
            if (!is_bool(strpos($inthat, $texto1)))
            return substr($inthat, strpos($inthat,$texto1)+strlen($texto1));
        }
    
        protected function between ($texto1, $that, $inthat){
            return $this->before ($that, $this->after($texto1, $inthat));
        }
    
        protected function before ($texto1, $inthat){
            
            return substr($inthat, 0, strpos($inthat, $texto1));
        }
    }
?>