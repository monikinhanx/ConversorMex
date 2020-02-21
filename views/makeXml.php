<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make XML</title>
</head>
<body>
    <?php
        $path = __DIR__."/origem/modelo1.xml";        
        $xml = simplexml_load_file($path);

        foreach($xml->Recording as $reg){
            $nome_arquivo = __DIR__."/destino/".substr($reg->Info->Filename, 0, strpos($reg->Info->Filename, ".wav")).".xml";
            $data_hora = substr($reg->Info->RecordingDateTime, 0, strpos($reg->Info->RecordingDateTime, ".000"));
            $string = "<?xml version='1.0' encoding='UTF-8'?>
<recordings>
    <recording>
        <Projeto>STEFANINI</Projeto>
        <Id_Venda>{$reg->Info->RecordingId}</Id_Venda>
        <Id_Operador>{$reg->CallData->CallId}</Id_Operador>
        <Nome_Operador>{$reg->Info->Agent}</Nome_Operador>
        <Contrato>{$reg->CallData->DNIS}</Contrato>
        <Supervisor>João Santos</Supervisor>
        <Telefone>{$reg->CallData->CallingParty}</Telefone>
        <Data_Hora_Inicio>$data_hora</Data_Hora_Inicio>
        <Nome_Do_Arquivo>{$reg->Info->Filename}</Nome_Do_Arquivo>
        <Campanha>NESTLE</Campanha>
        <Tipificacao>{$reg->CallData->Service}</Tipificacao>
    </recording>
</recordings>";
            file_put_contents($nome_arquivo, $string);
        }
    ?>
</body>
</html>