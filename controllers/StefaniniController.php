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
                case "sftp":
                    $this->viewSftp(); //Mostra pagina inicial
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
                $listaAgentes["agentes"]["{$reg->Info->Agent}"]["cont"] = 0;

                $nome_arquivo = $path."/xml/".substr($reg->Info->Filename, 0, -4).".xml";
                array_push($arquivosXml, substr($reg->Info->Filename, 0, -4).".xml");
            $data_hora = substr($reg->Info->RecordingDateTime, 0, strpos($reg->Info->RecordingDateTime, ".000"));
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
            $path = $_SERVER['DOCUMENT_ROOT']."/MexConsulting/views/uploadStefanini/";
            $arquivos = isset($_FILES['path']) ? $_FILES['path'] : false;
            $listaAgentes = $_SESSION['listaAgentes'];
            $selecionado = [];
            $naoSelecionado = [];
            $naoEncontrado = [];
            $foraPadrao = [];
            $naoAudio = [];
            $totalArquivos = [];
            $usado = false;

            for ($controle = 0; $controle < count($arquivos['name']); $controle++){
                array_push($totalArquivos, $arquivos['name'][$controle]);
                $duracao = $this->wavDur($arquivos['tmp_name'][$controle]);

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
                    if($duracao['hora'] >= 1 || ($duracao['hora'] == 0 && $duracao['minuto'] == 0 && $duracao['segundo'] < 30) || $arquivos['size'][$controle] == 0){
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
                        if(in_array($arquivos['name'][$controle], $agente["audios"])){
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
                        if($cont < 2){
                            foreach($listaAgentes["agentes"][$chave]["audios"] as $audio){
                                if($arquivos['name'][$controle] == $audio){
                                    $destino = $path."selecionado/".$arquivos['name'][$controle];
                                    array_push($selecionado,$arquivos['name'][$controle]);
                                    move_uploaded_file($arquivos['tmp_name'][$controle], $destino);
                                    $cont++;
                                    $listaAgentes["agentes"][$chave]["cont"] = $cont;
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
            
            $_SESSION["selecionado"] = $selecionado;
            $_SESSION["naoSelecionado"] = $naoSelecionado;
            $_SESSION["naoEncontrado"] = $naoEncontrado;
            $_SESSION["foraPadrao"] = $foraPadrao;
            $_SESSION["naoAudio"] = $naoAudio;
            $_SESSION["totalArquivos"] = $totalArquivos;

            include "views/Stefanini/amostra.php";
        }

        private function viewSftp(){
            set_time_limit(0);
            $xmlEnviado = [];
            $audioEnviado = [];
            $xmlErro = [];
            $audioErro = [];
            $pathAudio = $_SERVER['DOCUMENT_ROOT']."/MexConsulting/views/uploadStefanini/selecionado/";
            $pathXml = $_SERVER['DOCUMENT_ROOT']."/MexConsulting/views/uploadStefanini/xml/";
            $pathGeral = $_SERVER['DOCUMENT_ROOT']."/MexConsulting/views/uploadStefanini/";
            
            foreach($_SESSION["selecionado"] as $audio){
                if($this->sftpConnection($audio,$pathAudio)){
                    array_push($audioEnviado,$audio);
                }else{
                    array_push($audioErro,$audio);
                }
                unlink($pathAudio.$audio);
            }

            foreach($_SESSION["selecionado"] as $audio){
                foreach($_SESSION["arquivosXml"] as $xml){
                    if((substr($audio, 0, -4).".xml") == $xml){
                        if($this->sftpConnection($xml,$pathXml)){
                            array_push($xmlEnviado,$audio);
                        }else{
                            array_push($xmlErro,$audio);
                        }
                        $key = array_search($xml,$_SESSION["arquivosXml"]);
                        unset($_SESSION["arquivosXml"][$key]);
                        unlink($pathXml.$xml);
                    }
                }
            }

            foreach($_SESSION["naoSelecionado"] as $naoSelecionado){
                unlink($pathGeral."/naoSelecionado/".$naoSelecionado);
            }
            foreach($_SESSION["naoEncontrado"] as $naoEncontrado){
                unlink($pathGeral."/naoEncontrado/".$naoEncontrado);
            }
            foreach($_SESSION["foraPadrao"] as $foraPadrao){
                unlink($pathGeral."/foraPadrao/".$foraPadrao);
            }
            foreach($_SESSION["naoAudio"] as $naoAudio){
                unlink($pathGeral."/naoAudio/".$naoAudio);
            }
            foreach($_SESSION["arquivosXml"] as $xml){
                unlink($pathXml.$xml);
            }
            
            $_SESSION['xmlEnviado'] = $xmlEnviado;
            $_SESSION['audioEnviado'] = $audioEnviado;
            $_SESSION['xmlErro'] = $xmlErro;
            $_SESSION['audioErro'] = $audioErro;

            include "views/Stefanini/sftp.php";
        }

        private function sftpConnection($arquivo,$caminho){
            set_time_limit(0);
            $servidor = "uploads.callminer.net";
            $porta = 22;
            $caminho_absoluto = '/Asterisk/';

            $con_id = ssh2_connect($servidor,$porta) or die( 'Não conectou em: '.$servidor );
            $logou = ssh2_auth_password($con_id, 'Mex_Stefanini_FTP', 'nU4vK@@b7ePz^mhs');
            $sftp = ssh2_sftp($con_id);
            $stream = fopen('ssh2.sftp://'.$sftp.$caminho_absoluto.$arquivo, 'w');
            $data_to_send = file_get_contents($caminho.$arquivo);
            if(fwrite($stream, $data_to_send) === false){
                fclose($stream);
                return false;
            }else{
                fclose($stream);
                return true;
            }            
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