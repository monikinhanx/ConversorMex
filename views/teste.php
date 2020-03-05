<?php

    

    $path = __DIR__."/chat/";
    $diretorio = dir($path);
    $cont = 1;

    echo "Lista de Arquivos do diretório '<strong>".$path."</strong>':<br />";
    while($arquivo = $diretorio -> read()){
        if(strlen($arquivo) > 3){
            echo "$cont => <a href='".$path.$arquivo."'>".$arquivo."</a><br />";
            $cont++;
        }
    }
    $diretorio -> close();

    // $teste = 0;
    // while($teste < 5){
    //     echo $teste."<br>";
    //     sleep(5);
    //     $teste++;
    // }
?>