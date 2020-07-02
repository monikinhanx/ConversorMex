<?php
    include_once 'Conexao.php';
    
    class Ingestion extends Conexao{
        public function relatorios($username,$StartDate,$EndDate,$resultado){
            $db = parent::criarConexao();

            $query = $db->prepare("INSERT INTO relatorios (username,StartDate,EndDate,resultado) VALUES (:username,STR_TO_DATE(:StartDate,'%Y-%m-%d'),STR_TO_DATE(:EndDate,'%Y-%m-%d'),:resultado)");
            $query->bindValue(":username", $username);
            $query->bindValue(":StartDate", $StartDate);
            $query->bindValue(":EndDate", $EndDate);
            $query->bindValue(":resultado", $resultado);
            $resultado = $query->execute();

            return $resultado;
        }
    }
?>