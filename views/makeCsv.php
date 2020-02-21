<?php

function after ($texto1, $string)
{
    if (!is_bool(strpos($string, $texto1)))
    return substr($string, strpos($string,$texto1)+strlen($texto1));
};

function before ($texto1, $string)
{
    return substr($string, 0, strpos($string, $texto1));
};

function between ($texto1, $texto2, $string)
{
    return before ($texto2, after($texto1, $string));
};

function strrevpos($instr, $needle)
{
    $rev_pos = strpos (strrev($instr), strrev($needle));
    if ($rev_pos===false) return false;
    else return strlen($instr) - $rev_pos - strlen($needle);
};

function before_last ($texto1, $string)
{
    return substr($string, 0, strrevpos($string, $texto1));
};

$path = __DIR__."/chat/";
$diretorio = dir($path);
$resultado = [];

echo "Lista de Arquivos do diretório '\<strong>".$path."</strong>':<br />";

while($arquivo = $diretorio -> read()){
    echo "<a href='".$path.$arquivo."'>".$arquivo."</a><br />";

    if(strlen($arquivo) > 3){

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

                $registro = array_combine($cabecalho, $linha);
                $data = before("T", $registro['PostDateTime'])." ".between("T", ".", $registro['PostDateTime']);

                if(empty($string)){
                    $string = "{
            \"Metadata\": [
                {
                    \"Key\": \"UDF_text_01\",
                    \"Value\": \"Nubank\"
                },
                {
                    \"Key\": \"ClientID\",
                    \"Value\": \"{$registro['source_id']}\"
                },
                {
                    \"Key\": \"UDF_text_10\",
                    \"Value\": \"{$registro['subject_id']}\"
                },
                {
                    \"Key\": \"UDF_text_09\",
                    \"Value\": \"{$registro['agent']}\"
                },
                {
                    \"Key\": \"Agent\",
                    \"Value\": \"{$registro['agent']}\"
                },
                {
                    \"Key\": \"Dept\",
                    \"Value\": \"{$registro['actor_squad']}\"
                },
                {
                    \"Key\": \"ExitStatus\",
                    \"Value\": \"{$registro['status']}\"
                },
                {
                    \"Key\": \"UDF_text_13\",
                    \"Value\": \"{$registro['customer_tag_formal']}\"
                },
                {
                    \"Key\": \"UDF_text_16\",
                    \"Value\": \"{$registro['customer_tag_deficiente']}\"
                },
                {
                    \"Key\": \"ClientCaptureDate\",
                    \"Value\": \"$data\"
                },
                {
                    \"Key\": \"UDF_text_12\",
                    \"Value\": \"{$registro['activity_type']}\"
                }
                ],
            \"MediaType\": \"Chat\",
            \"ClientCaptureDate\": \"{$registro['PostDateTime']}\",
            \"SourceId\": \"TradeCall\",
            \"Transcript\": [";
                }

                $chat = "{
                    \"Speaker\": {$registro['Speaker']},
                    \"Text\": \"{$registro['Text']}\",
                    \"PostDateTime\": \"{$registro['PostDateTime']}\",
                    \"TextInformation\": \"{$registro['TextInformation']}\"
                },";

                $transcript = "";

                $transcript .= $chat;

                $string .= $chat;

            }

            $endData = "]
            }";

            $string = before_last(",", $string);
            
            $string .= $endData;

            $servidor = "https://ingestion.callminer.net/api/transcript";

            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',                    
                    'header' => "Authorization: JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6Ik51dmV0b05HTV9Jbmdlc3Rpb25Vc2VyXzYyMDNjMmUyMjc0MDQ2MmJhZTVhMDU3YWY4OWU3NTg0QGNhbGxtaW5lci5jb20iLCJlbWFpbCI6Ik51dmV0b05HTV9Jbmdlc3Rpb25Vc2VyXzYyMDNjMmUyMjc0MDQ2MmJhZTVhMDU3YWY4OWU3NTg0QGNhbGxtaW5lci5jb20iLCJhY3RvcnQiOiJOdXZldG8iLCJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9sb2NhbGl0eSI6ImVuLVVTIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9leHBpcmF0aW9uIjoiMDIvMDYvMjAyMiAyMTowMTowNiIsIm5iZiI6MTU4MTEwOTI2NiwiZXhwIjoxNjQ0MTgxMjY2LCJpYXQiOjE1ODExMDkyNjYsImlzcyI6Imh0dHA6Ly9hcGkuY2FsbG1pbmVyLm5ldCIsImF1ZCI6Imh0dHA6Ly9hcGkuY2FsbG1pbmVyLm5ldCJ9.4ofZ680TAGqwtuXRXo4gjXyn1VDNtZqI7l9wYfw3XJ8\r\n".
                                "Content-type: application/json; charset=utf-8\r\n",
                    'content' => $string                               
                )
            ));

            $contents = file_get_contents($servidor, null, $context);            
            $resposta = json_decode($contents);

            array_push($resultado, $resposta);

            
            fclose($f);
        }
    }  
}
$diretorio -> close();

var_dump($resultado);
// echo $resposta;

?>