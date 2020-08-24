<?php
    include_once 'Conexao.php';
    
    class Email extends Conexao{
        public function contatos($nome,$email,$telefone,$empresa,$mensagem,$enviado,$msg_log){
            $db = parent::criarConexao();

            $query = $db->prepare("INSERT INTO contatos (nome,email,telefone,empresa,mensagem,enviado,msg_log) VALUES (:nome,:email,:telefone,:empresa,:mensagem,:enviado,:msg_log)");
            $query->bindValue(":nome", $nome);
            $query->bindValue(":email", $email);
            $query->bindValue(":telefone", $telefone);
            $query->bindValue(":empresa", $empresa);
            $query->bindValue(":mensagem", $mensagem);
            $query->bindValue(":enviado", $enviado);
            $query->bindValue(":msg_log", $msg_log);
            $resultado = $query->execute();
            
            return $resultado;
        }
        
        public function falhas($origem,$nome,$email,$telefone,$empresa,$mensagem,$enviado,$msg_log){
            $db = parent::criarConexao();
            
            $query = $db->prepare("INSERT INTO falhas (origem,nome,email,telefone,empresa,mensagem,enviado,msg_log) VALUES (:origem,:nome,:email,:telefone,:empresa,:mensagem,:enviado,:msg_log)");
            $query->bindValue(":origem", $origem);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":email", $email);
            $query->bindValue(":telefone", $telefone);
            $query->bindValue(":empresa", $empresa);
            $query->bindValue(":mensagem", $mensagem);
            $query->bindValue(":enviado", $enviado);
            $query->bindValue(":msg_log", $msg_log);
            $resultado = $query->execute();

            return $resultado;
        }
    }
?>

<!-- 
    
create table contatos(
	id_contato int primary key auto_increment,
    nome varchar(200) not null,
    email varchar(100) not null,
    telefone varchar(20) not null,
    empresa varchar(100) not null,
    mensagem varchar(1000) not null,
    enviado bool not null,
    msg_log varchar(1000) not null,
    data_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table falhas(
	id_falha int primary key auto_increment,
    origem varchar(20) not null,
    nome varchar(200) not null,
    email varchar(100) not null,
    telefone varchar(20) not null,
    empresa varchar(100) not null,
    mensagem varchar(1000) not null,
    enviado bool not null,
    msg_log varchar(1000) not null,
    data_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-->