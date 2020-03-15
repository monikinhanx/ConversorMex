<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function viewHome(){
        return view('inicio');
    }

    public function viewLoading(){
        return view('loading');
    }

    public function viewErro(){
        return view('erro');
    }

    public function viewPath(){
        return view('path');
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

    public function viewMetadados(Request $request){
        set_time_limit(100000);
        $path = "upload/";

        $arquivos = isset($_FILES['path']) ? $_FILES['path'] : FALSE;
            
        for ($controle = 0; $controle < count($arquivos['name']); $controle++){ 
            $destino = $path."/".$arquivos['name'][$controle];
            move_uploaded_file($arquivos['tmp_name'][$controle], $destino);
        }
        
        $diretorio = dir($path);
        $resultado = [];
        $erro = [];
        
        while($arquivo = $diretorio -> read()){
            if(strlen($arquivo) > 3 && $arquivo != "upload.txt"){
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
            \"Key\": \"Agent\",
            \"Value\": \"{$registro['agent']}\"
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
            \"Key\": \"UDF_text_13\",
            \"Value\": \"{$registro['actor_affiliation']}\"
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
        // dd($resultado);
        $qtdRes = count($resultado);
        $qtdErro = count($erro);
        return view('metadados', ["resultado"=>$resultado, "qtdRes"=>$qtdRes, "qtdErro"=>$qtdErro, "erro"=>$erro]);
    }

    public function viewApi(Request $request){

        set_time_limit(100000);
        
        $string = json_decode($request->metadados);
        $erro = 0;
        $ok =  0;
        $resultado = [];

        foreach($string as $chat){
            // dd($chat);
            $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',                    
                        'header' => "Authorization: JWT eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpLmNhbGxtaW5lci5uZXQiLCJhdWQiOiJodHRwOi8vYXBpLmNhbGxtaW5lci5uZXQiLCJuYmYiOjE1ODI4MjcyMTYsImV4cCI6MTY0NTg5OTIxNiwidW5pcXVlX25hbWUiOiJNRVgtTnViYW5rX0luZ2VzdGlvblVzZXJfZGEyOGQ1M2E1NjVjNDY4MDlhNzZiMDIwODdlMzlhNzVAY2FsbG1pbmVyLmNvbSIsImVtYWlsIjoiTUVYLU51YmFua19Jbmdlc3Rpb25Vc2VyX2RhMjhkNTNhNTY1YzQ2ODA5YTc2YjAyMDg3ZTM5YTc1QGNhbGxtaW5lci5jb20iLCJhY3RvcnQiOiJNRVgtTnViYW5rIiwiaHR0cDovL3NjaGVtYXMueG1sc29hcC5vcmcvd3MvMjAwNS8wNS9pZGVudGl0eS9jbGFpbXMvbG9jYWxpdHkiOiJlbi1VUyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvZXhwaXJhdGlvbiI6IjAyLzI2LzIwMjIgMTg6MTM6MzYifQ.Mz_WmcC5wDUiEbehFQQZ2bssshS6n1eWu_NkF4X4mL8\r\n"."Content-type: application/json; charset=utf-8\r\n",
                        'content' => $chat                            
                    )
                )
            );
    
            $contents = file_get_contents("https://ingestion.callminer.net/api/transcript", null, $context);            
            $resposta = json_decode($contents);

            array_push($resultado, $resposta);

            is_null($resposta) ? $erro++ : $ok++;
        }
        
        return view('api',["erro"=>$erro, "ok"=>$ok, "resultado"=>$resultado]);
    }
}
