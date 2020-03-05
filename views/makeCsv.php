<?php

    set_time_limit(100000);

    include_once('views/includes/substrfunctions.php');

    $path = __DIR__."/chat/";
    $diretorio = dir($path);
    $resultado = [];
    
    echo "Lista de Arquivos do diretório '\<strong>".$path."</strong>':<br />";
    
    while($arquivo = $diretorio -> read()){
        
        if(strlen($arquivo) > 3){
            echo "<a href='".$path.$arquivo."'>".$arquivo."</a><br />";
    
            $delimitador = ',';
            $cerca = '"';
            $f = fopen($path.$arquivo, 'r');
            if ($f) { 
                $cabecalho = fgetcsv($f, 0, $delimitador, $cerca);
                $string = "";
    
                while (!feof($f)) { 
                    $linha = fgetcsv($f, 0, $delimitador, $cerca);
                    if (!$linha) {
                        continue;
                    }

                    var_dump($linha);
                    
                    echo "<BR><BR><BR>";
                    
                    // var_dump($linha);

                    $registro = array_combine($cabecalho, $linha);

                    // echo addslashes($registro['Text'])."<br>";
                    // exit;
                    $data = before("T", $registro['PostDateTime'])." ".between("T", ".", $registro['PostDateTime']);
                    
                    if(empty($string)){
                        $string = "{
                \"Metadata\": [
                    {
                        \"Key\": \"ClientCaptureDate\",
                        \"Value\": \"$data\"
                    },
                    {
                        \"Key\": \"ClientID\",
                        \"Value\": \"{$registro['source_id']}\"
                    },
                    {
                        \"Key\": \"Agent\",
                        \"Value\": \"{$registro['agent']}\"
                    },
                    {
                        \"Key\": \"Dept\",
                        \"Value\": \"{$registro['actor_affiliation']}\"
                    },
                    {
                        \"Key\": \"ExitStatus\",
                        \"Value\": \"{$registro['status']}\"
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
                        \"Key\": \"UDF_Int_01\",
                        \"Value\": \"{$registro['net_time_spent']}\"
                    }
                    ],
                \"MediaType\": \"Chat\",
                \"ClientCaptureDate\": \"{$registro['PostDateTime']}\",
                \"SourceId\": \"TeraVoz\",
                \"Transcript\": [";
                    }
                    
                    $texto = addslashes($registro['Text']);
    
                    $chat = "{
                        \"Speaker\": {$registro['Speaker']},
                        \"Text\": \"{$texto}\",
                        \"PostDateTime\": \"{$registro['PostDateTime']}\",
                        \"TextInformation\": \"{$registro['TextInformation']}\"
                    },";
    
                    $string .= $chat;
    
                }
    
                $endData = "]
                }";
    
                $string = before_last(",", $string);
                
                $string .= $endData;

                echo "<br><br>";

                var_dump($string);
                exit;

                $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',                    
                        'header' => "Authorization: JWT eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpLmNhbGxtaW5lci5uZXQiLCJhdWQiOiJodHRwOi8vYXBpLmNhbGxtaW5lci5uZXQiLCJuYmYiOjE1ODI4MjcyMTYsImV4cCI6MTY0NTg5OTIxNiwidW5pcXVlX25hbWUiOiJNRVgtTnViYW5rX0luZ2VzdGlvblVzZXJfZGEyOGQ1M2E1NjVjNDY4MDlhNzZiMDIwODdlMzlhNzVAY2FsbG1pbmVyLmNvbSIsImVtYWlsIjoiTUVYLU51YmFua19Jbmdlc3Rpb25Vc2VyX2RhMjhkNTNhNTY1YzQ2ODA5YTc2YjAyMDg3ZTM5YTc1QGNhbGxtaW5lci5jb20iLCJhY3RvcnQiOiJNRVgtTnViYW5rIiwiaHR0cDovL3NjaGVtYXMueG1sc29hcC5vcmcvd3MvMjAwNS8wNS9pZGVudGl0eS9jbGFpbXMvbG9jYWxpdHkiOiJlbi1VUyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvZXhwaXJhdGlvbiI6IjAyLzI2LzIwMjIgMTg6MTM6MzYifQ.Mz_WmcC5wDUiEbehFQQZ2bssshS6n1eWu_NkF4X4mL8\r\n"."Content-type: application/json; charset=utf-8\r\n",
                        'content' => $string                            
                    )
                ));
    
                $contents = file_get_contents("https://ingestion.callminer.net/api/transcript", null, $context);            
                $resposta = json_decode($contents);

                array_push($resultado, $resposta);

                fclose($f);
            }
        }  
    }
    $diretorio -> close();
    
    var_dump($resultado);

?>