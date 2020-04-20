<?php
    session_start();

    class StefaniniController{
        public function acao($rotas){
            switch($rotas){
                case "stefanini":
                    $this->viewStefanini(); //Mostra pagina inicial
                break;
                case "operacao":
                    $this->viewOperacao(); //Mostra pagina inicial
                break;
                case "selecionaoperacao":
                    $this->selecionaOperacao(); //Mostra pagina inicial
                break;
                case "nestle":
                    $this->viewNestle(); //Mostra pagina inicial
                break;
                case "boticario":
                    $this->viewBoticario(); //Mostra pagina inicial
                break;
                case "audio":
                    $this->viewAudio(); //Mostra pagina inicial
                break;
                case "amostra":
                    $this->viewAmostra(); //Mostra pagina inicial
                break;
                case "cm":
                    $this->viewCm(); //Mostra pagina inicial
                break;
            }
        }

        private function viewStefanini(){
            include "views/Stefanini/stefanini.php";
        }

        private function viewOperacao(){
            include "views/Stefanini/operacao.php";
        }

        private function viewNestle(){
            include "views/Stefanini/nestle.php";
        }

        private function viewBoticario(){
            include "views/Stefanini/boticario.php";
        }

        private function selecionaOperacao(){
            if($_POST['operacao'] == "nestle"){
                $_SESSION['erroOperacao'] = "";
                $_SESSION['operacao'] = $_POST['operacao'];
                header('Location:/?nestle');
            }elseif($_POST['operacao'] == "boticario"){
                $_SESSION['erroOperacao'] = "";
                $_SESSION['operacao'] = $_POST['operacao'];
                header('Location:/?boticario');
            }else{
                $_SESSION['erroOperacao'] = "Escolha uma operação válida!";
                header('Location:/?operacao');
            }
        }

        private function xmlNestle(){
            set_time_limit(0);
            $path = $_SERVER['DOCUMENT_ROOT']."/MexConsulting/views/uploadStefanini/";
            $arquivos = isset($_FILES['path']) ? $_FILES['path'] : false;
            $destino = $path.$arquivos['name'];
            move_uploaded_file($arquivos['tmp_name'], $destino);

            $xml = simplexml_load_file($destino);
            $listaAgentes = ["total"=>0,"agentes"=>[]];
            $arquivosXml = [];

            foreach($xml->Recording as $reg){
                $tot = $listaAgentes["total"];
                $tot++;
                $listaAgentes["total"] = $tot;
                if(array_key_exists("{$reg->Info->Agent}", $listaAgentes["agentes"])){
                    $cont = count($listaAgentes["agentes"]["{$reg->Info->Agent}"]["audios"]);
                    $cont++;
                    $listaAgentes["agentes"]["{$reg->Info->Agent}"]["audios"]["{$cont}"] = "{$reg->Info->Filename}";
                }else{
                    $listaAgentes["agentes"]["{$reg->Info->Agent}"] = ["audios"=>["1"=>"{$reg->Info->Filename}"]];
                }
                $listaAgentes["agentes"]["{$reg->Info->Agent}"]["cont"] = [];

                $data_hora = substr($reg->Info->RecordingDateTime, 0, strpos($reg->Info->RecordingDateTime, ".000"));
                $nome_arquivo = $path."/xml/".substr($reg->Info->Filename, 0, -4).".xml";
                array_push($arquivosXml, substr($reg->Info->Filename, 0, -4).".xml");
            
            $string = "<?xml version='1.0' encoding='UTF-8'?>
<recordings>
    <recording>
        <Projeto>STEFANINI</Projeto>
        <Call_Id>{$reg->Info->RecordingId}</Call_Id>
        <Nome_Do_Arquivo>{$reg->Info->Filename}</Nome_Do_Arquivo>
        <Telefone>{$reg->CallData->CallingParty}</Telefone>
        <Data_Hora_Inicio>$data_hora</Data_Hora_Inicio>
        <Id_Operador>{$reg->CallData->CallId}</Id_Operador>
        <Nome_Operador>{$reg->Info->Agent}</Nome_Operador>
        <Supervisor>João Santos</Supervisor>
        <Gestor>-</Gestor>
        <Campanha>NESTLE</Campanha>
        <Celula>{$reg->CallData->Service}</Celula>
        <Site>PINHEIROS</Site>
    </recording>
</recordings>";
            file_put_contents($nome_arquivo, $string);
            }
            unlink($destino);
            $_SESSION["arquivosXml"] = $arquivosXml;

            return $listaAgentes;
        }

        private function xmlBoticario(){
            set_time_limit(0);
        }

        private function viewAudio(){
            if($_SESSION['operacao'] == "nestle"){
                $listaAgentes = $this->xmlNestle();
            }elseif($_SESSION['operacao'] == "boticario"){
                $listaAgentes = $this->xmlBoticario();
            }
            $_SESSION['listaAgentes'] = $listaAgentes;
            include "views/Stefanini/audio.php";
        }

        private function viewAmostra(){
            set_time_limit(0);
            $maxMin = 5;
            top:
            $path = $_SERVER['DOCUMENT_ROOT']."/MexConsulting/views/uploadStefanini/";
            $arquivos = isset($_FILES['path']) ? $_FILES['path'] : false;
            $listaAgentes = $_SESSION['listaAgentes'];
            $tma = $_POST['tma'] * 60;
            $qtdAudio = $_POST['qtdAudio'];
            $selecionado = [];
            $naoSelecionado = [];
            $naoEncontrado = [];
            $foraPadrao = [];
            $naoAudio = [];
            $totalArquivos = [];
            $usado = false;

            for ($controle = 0; $controle < count($arquivos['name']); $controle++){
                $duracao = $this->wavDur($arquivos['tmp_name'][$controle]);
                array_push($totalArquivos, $arquivos['name'][$controle]);

                // Testando se os arquivos não são de audio
                if(!$usado){
                    if($arquivos['type'][$controle] != "audio/wav"){
                        $destino = $path."naoAudio/".$arquivos['name'][$controle];
                        array_push($naoAudio,$arquivos['name'][$controle]);
                        move_uploaded_file($arquivos['tmp_name'][$controle], $destino);
                        $usado = true;
                    }
                }

                //Testando se está fora do padrão
                if(!$usado){
                    if(($duracao['hora'] >= 0 && $duracao['minuto'] > $maxMin && $duracao['segundo'] >= 0) || ($duracao['hora'] == 0 && $duracao['minuto'] == 0 && $duracao['segundo'] < 30) || $arquivos['size'][$controle] == 0){
                        $destino = $path."foraPadrao/".$arquivos['name'][$controle];
                        array_push($foraPadrao,$arquivos['name'][$controle]);
                        move_uploaded_file($arquivos['tmp_name'][$controle], $destino);
                        $usado = true;
                    }
                }

                //Testando se está na amostra
                if(!$usado){
                    $achou = false;
                    foreach($listaAgentes["agentes"] as $agente){
                        if(!in_array($arquivos['name'][$controle], $agente["audios"])){
                            continue;
                        }else{
                            $achou = true;
                        }
                    }
                    if(!$achou){
                        $destino = $path."naoEncontrado/".$arquivos['name'][$controle];
                        array_push($naoEncontrado,$arquivos['name'][$controle]);
                        move_uploaded_file($arquivos['tmp_name'][$controle], $destino); 
                        $usado = true;           
                    }
                }

                //Iniciando seleção da amostra
                reset($listaAgentes["agentes"]);
                if(!$usado){
                    while ($agente = current($listaAgentes["agentes"])) {
                        $chave = key($listaAgentes["agentes"]);
                        $cont = $listaAgentes["agentes"][$chave]["cont"];
                        if($cont < $qtdAudio){
                            foreach($listaAgentes["agentes"][$chave]["audios"] as $audio){
                                if($arquivos['name'][$controle] == $audio){
                                    $destino = $path."selecionado/".$arquivos['name'][$controle];
                                    array_push($selecionado,[$arquivos['name'][$controle],$arquivos['size'][$controle]]);
                                    array_push($listaAgentes["agentes"][$chave]["cont"],[$arquivos['name'][$controle], $duracao]);
                                    move_uploaded_file($arquivos['tmp_name'][$controle], $destino);
                                    $usado = true;
                                }
                            }
                        }
                        next($listaAgentes["agentes"]);
                    }
                }

                //Verificando não selecionados
                if(!$usado){
                    if(!in_array($arquivos['name'][$controle], $selecionado)){
                        $destino = $path."naoSelecionado/".$arquivos['name'][$controle];
                        array_push($naoSelecionado,$arquivos['name'][$controle]);
                        move_uploaded_file($arquivos['tmp_name'][$controle], $destino);
                        $usado = true;
                    }
                }

                //Voltando USADO pro status inicial
                $usado = false;
            }

            $totCont = 0;
            $hora = 0;
            $minuto = 0;
            $segundo = 0;

            reset($listaAgentes["agentes"]);
            while ($agente = current($listaAgentes["agentes"])) {
                $chaveAgente = key($listaAgentes["agentes"]);
                $totCont += count($listaAgentes["agentes"][$chaveAgente]["cont"]);
                foreach($listaAgentes["agentes"][$chaveAgente]["cont"] as $sel){
                    foreach($sel as $s){
                        if(is_array($s)){
                            $hora += $s["hora"];
                            $minuto += $s["minuto"];
                            $segundo += $s["segundo"];
                        }
                    }
                }
                next($listaAgentes["agentes"]);
            }

            $minuto += ($hora * 60);
            $segundo += ($minuto *60);
            $tma = $segundo / $totCont;

            $mintma = intval($tma/60);
            $segtma = $tma % 60;

            $tmaFormatado = $mintma."m".$segtma."s";
            
            $_SESSION["selecionado"] = $selecionado;
            $_SESSION["naoSelecionado"] = $naoSelecionado;
            $_SESSION["naoEncontrado"] = $naoEncontrado;
            $_SESSION["foraPadrao"] = $foraPadrao;
            $_SESSION["naoAudio"] = $naoAudio;
            $_SESSION["totalArquivos"] = $totalArquivos;
            $_SESSION["tmaFormatado"] = $tmaFormatado;
            $_SESSION['listaAgentes'] = $listaAgentes;

            include "views/Stefanini/amostra.php";
        }

        private function viewCm(){
            set_time_limit(0);
            $erro = [];
            $resultado = [];

            $path = $_SERVER['DOCUMENT_ROOT']."/MexConsulting/views/uploadStefanini/";
            
            foreach($_SESSION["selecionado"] as $selec){
                $xml = simplexml_load_file($path."xml/".substr($selec[0], 0, -4).".xml");

                $string = "{
    \"Metadata\": [
        {
            \"Key\": \"ClientCaptureDate\",
            \"Value\": \"$reg->Data_Hora_Inicio\"
        },
        {
            \"Key\": \"ClientID\",
            \"Value\": \"$reg->Call_Id\"
        },
        {
            \"Key\": \"AudioFileLocation\",
            \"Value\": \"$reg->Nome_Do_Arquivo\"
        },
        {
            \"Key\": \"Agent\",
            \"Value\": \"$reg->Nome_Operador\"
        },
        {
            \"Key\": \"ANI\",
            \"Value\": \"$reg->Telefone\"
        },
        {
            \"Key\": \"UDF_text_01\",
            \"Value\": \"$reg->Projeto\"
        },
        {
            \"Key\": \"UDF_text_02\",
            \"Value\": \"$reg->Id_Operador\"
        },
        {
            \"Key\": \"UDF_text_03\",
            \"Value\": \"$reg->Supervisor\"
        },
        {
            \"Key\": \"UDF_text_04\",
            \"Value\": \"$reg->Gestor\"
        },
        {
            \"Key\": \"UDF_text_05\",
            \"Value\": \"$reg->Campanha\"
        },
        {
            \"Key\": \"UDF_text_06\",
            \"Value\": \"$reg->Celula\"
        },
        {
            \"Key\": \"UDF_text_07\",
            \"Value\": \"$reg->Site\"
        }
    ],
    \"TotalMediaLength\": $selec[1],
    \"MediaType\": \"audio/wav\",
    \"ClientCaptureDate\": \"$data\",
    \"VoicePrintIdentifier\": \"$reg->Nome_Operador\",
    \"SourceId\": \"Asterisk\"
}";

                $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',                    
                        'header' => "Authorization: JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6Ik1FWC1TdGVmYW5pbmlfSW5nZXN0aW9uVXNlcl9hNzMwMWVkMTQ0YjI0NDFhOTJlN2ZiOGE2NGFiNGZkNEBjYWxsbWluZXIuY29tIiwiZW1haWwiOiJNRVgtU3RlZmFuaW5pX0luZ2VzdGlvblVzZXJfYTczMDFlZDE0NGIyNDQxYTkyZTdmYjhhNjRhYjRmZDRAY2FsbG1pbmVyLmNvbSIsImFjdG9ydCI6Ik1FWC1TdGVmYW5pbmkiLCJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9sb2NhbGl0eSI6ImVuLVVTIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9leHBpcmF0aW9uIjoiMDMvMzEvMjAyMiAxMzoyODoyOSIsIm5iZiI6MTU4NTY2MTMwOSwiZXhwIjoxNjQ4NzMzMzA5LCJpYXQiOjE1ODU2NjEzMDksImlzcyI6Imh0dHA6Ly9hcGkuY2FsbG1pbmVyLm5ldCIsImF1ZCI6Imh0dHA6Ly9hcGkuY2FsbG1pbmVyLm5ldCJ9.2_TO34z4vgXbj4IRVNkgRY4ymbRohEvGk7MOEqaw7_Y\r\n"."Content-type: application/json; charset=utf-8\r\n",
                        'content' => $string                            
                    )
                ));

                $contents = file_get_contents("https://ingestion.callminer.net/api/session/metadatamedia", null, $context);            
                $resposta = json_decode($contents);

                if(is_null($resposta)){
                    array_push($erro, [$selec[0]=>$resposta]);
                }else{
                    $context = stream_context_create(array(
                        'http' => array(
                            'method' => 'POST',                    
                            'header' => "Authorization: JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6Ik1FWC1TdGVmYW5pbmlfSW5nZXN0aW9uVXNlcl9hNzMwMWVkMTQ0YjI0NDFhOTJlN2ZiOGE2NGFiNGZkNEBjYWxsbWluZXIuY29tIiwiZW1haWwiOiJNRVgtU3RlZmFuaW5pX0luZ2VzdGlvblVzZXJfYTczMDFlZDE0NGIyNDQxYTkyZTdmYjhhNjRhYjRmZDRAY2FsbG1pbmVyLmNvbSIsImFjdG9ydCI6Ik1FWC1TdGVmYW5pbmkiLCJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9sb2NhbGl0eSI6ImVuLVVTIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9leHBpcmF0aW9uIjoiMDMvMzEvMjAyMiAxMzoyODoyOSIsIm5iZiI6MTU4NTY2MTMwOSwiZXhwIjoxNjQ4NzMzMzA5LCJpYXQiOjE1ODU2NjEzMDksImlzcyI6Imh0dHA6Ly9hcGkuY2FsbG1pbmVyLm5ldCIsImF1ZCI6Imh0dHA6Ly9hcGkuY2FsbG1pbmVyLm5ldCJ9.2_TO34z4vgXbj4IRVNkgRY4ymbRohEvGk7MOEqaw7_Y\r\n"."Content-type: audio/wav\r\n"."Content-Length: {$selec[1]}\r\n",
                            'content' => file_get_contents($path."selecionado/".$selec[0]) 
                        )
                    ));
        
                    $contents = file_get_contents("https://ingestion.callminer.net/api/media/{$resposta->SessionId}", null, $context);$resposta = json_decode($contents);
                    
                    array_push($resultado, [$selec[0]=>$resposta]);
                }
            }

            foreach($_SESSION["selecionado"] as $selecionado){
                unlink($path."/selecionado/".$selecionado);
            }
            foreach($_SESSION["naoSelecionado"] as $naoSelecionado){
                unlink($path."/naoSelecionado/".$naoSelecionado);
            }
            foreach($_SESSION["naoEncontrado"] as $naoEncontrado){
                unlink($path."/naoEncontrado/".$naoEncontrado);
            }
            foreach($_SESSION["foraPadrao"] as $foraPadrao){
                unlink($path."/foraPadrao/".$foraPadrao);
            }
            foreach($_SESSION["naoAudio"] as $naoAudio){
                unlink($path."/naoAudio/".$naoAudio);
            }
            foreach($_SESSION["arquivosXml"] as $xml){
                unlink($path."/xml/".$xml);
            }
            
            $_SESSION['apiErro'] = $erro;
            $_SESSION['apiResultado'] = $resultado;
            
            include "views/Stefanini/cm.php";
        }
        
        private function wavDur($file) {
            $fp = fopen($file, 'r');
            if (fread($fp,4) == "RIFF") {
                fseek($fp, 20);
                $rawheader = fread($fp, 16);
                $header = unpack('vtype/vchannels/Vsamplerate/Vbytespersec/valignment/vbits',$rawheader);
                $pos = ftell($fp);
                while (fread($fp,4) != "data" && !feof($fp)) {
                    $pos++;
                    fseek($fp,$pos);
                }
                $rawheader = fread($fp, 4);
                $data = unpack('Vdatasize',$rawheader);
                $sec = $data['datasize']/$header['bytespersec'];
                $hours = intval(($sec / 60) / 60);
                $minutes = intval(($sec / 60) % 60);
                $seconds = intval($sec % 60);
                return ["hora"=>$hours, "minuto"=>$minutes, "segundo"=>$seconds];
                }
            }
    }
?>