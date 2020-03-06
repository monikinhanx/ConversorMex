<?php
    set_time_limit(100000);
    include_once('views/includes/substrfunctions.php');

    $path = "G:\Meu Drive\xmlBoticario/";
    $diretorio = dir($path);

    while($arquivo = $diretorio -> read()){
        if(strlen($arquivo) > 3){
            echo "<a href='".$path.$arquivo."'>".$arquivo."</a><br />";
            // $f = simplexml_load_file($path.$arquivo);
            // $f = simplexml_load_string($path.$arquivo);
            $f = file_get_contents($path.$arquivo);
            $troca = "</recording></recordings>";
            $acha = after('</Site>', $f);
            $abre = fopen($path.$arquivo, 'w');
            echo "<br><br><br>";
            // echo $f."<br><br><br>";
            // echo $f->asXML()."<br><br><br>";
            // echo after('</Site>', $f)."<br><br><br>";
            var_dump($f);
            $f = str_replace($acha, $troca, $f);
            // $f = preg_replace($acha, $troca, $f);
            echo "<br><br><br>";
            var_dump($f);
            // $f = simplexml_load_file($path.$arquivo);
            echo "<br><br><br>";
            // var_dump($f);
            fwrite($abre, $f);
            fclose($abre);
        }
    }

    $diretorio -> close();
?>