<?php
    if(isset($_SESSION['usuario'])){
        switch($_SESSION['usuario']->operacao){
            case "Mex":
                header('Location:/?mex');
            break;
            case "Nubank":
                header('Location:/?nubank');
            break;
            case "Stefanini":
                header('Location:/?stefanini');
            break;
        }
    }
?>