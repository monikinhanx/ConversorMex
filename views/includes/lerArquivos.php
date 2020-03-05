<?php
    function lerArquivos($path){
        $diretorio = dir($path);
        $arquivos = [];
        $cont = 1;

        while($arquivo = $diretorio -> read()){
            if(strlen($arquivo) > 3){
                echo "$cont => <a href='".$path.$arquivo."'>".$arquivo."</a><br />";
                $cont++;
            }
        }
        $diretorio -> close();
    }
?>