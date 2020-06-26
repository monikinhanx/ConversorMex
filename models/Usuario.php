<?php
    include_once 'Conexao.php';
    
    class Usuario extends Conexao{
        public function cadastrarUsuario($nome,$sobrenome,$operacao,$email){
            $db = parent::criarConexao(); //Sintaxe para chamar um metodo da classe pai
            $query = $db->prepare("INSERT INTO usuarios (nome,sobrenome,operacao,email) VALUES (?,?,?,?)"); //Prepara query para inserir dados no BD
            return $query->execute([$nome,$sobrenome,$operacao,$email]); //Executa query para inserir no BD
        }
        
        public function recuperaUsuario($email){
            $db = parent::criarConexao(); //Sintaxe para chamar um metodo da classe pai
            $query = $db->prepare("SELECT * FROM usuarios WHERE email = :email"); //Prepara e executa query para pegar todos os dados do usuario
            $query->bindValue(":email", $email);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_OBJ); //Transforma dados do BD em um array de Objetos
            return $resultado;
        }

        public function listarUsuarios(){
            $db = parent::criarConexao();
            $query = $db->prepare("SELECT * FROM usuarios");
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_OBJ);
            return $resultado;
        }

        public function alteraSenha($email,$senha){
            $db = parent::criarConexao();
            $query = $db->prepare("UPDATE usuarios SET senha = :senha WHERE email = :email");
            $query->bindValue(":email", $email);
            $query->bindValue(":senha", $senha);
            $resultado = $query->execute();
            return $resultado;
        }

        public function ultimoLogin($email){
            $db = parent::criarConexao();
            $query = $db->prepare("UPDATE usuarios SET ultimo_login = CURRENT_TIMESTAMP WHERE email = :email");
            $query->bindValue(":email", $email);
            $resultado = $query->execute();
            return $resultado;
        }
        public function deletarUsuario($id){
            $db = parent::criarConexao();
            $query = $db->prepare("DELETE FROM usuarios WHERE id = :id");
            $query->bindValue(":id", $id);
            $resultado = $query->execute();
            return $resultado;
        }

        public function alteraUsuario($id,$nome,$sobrenome,$operacao,$email){
            $db = parent::criarConexao();
            $query = $db->prepare("UPDATE usuarios SET nome = :nome, sobrenome = :sobrenome, operacao = :operacao, email = :email, WHERE id = :id");
            $query->bindValue(":id", $id);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":sobrenome", $sobrenome);
            $query->bindValue(":operacao", $operacao);
            $query->bindValue(":email", $email);
            $resultado = $query->execute();
            return $resultado;
        }

        public function cadastrarUploads($nome,$sobrenome,$operacao,$email){
            $db = parent::criarConexao(); //Sintaxe para chamar um metodo da classe pai
            $query = $db->prepare("INSERT INTO usuarios (nome,sobrenome,operacao,email) VALUES (?,?,?,?)"); //Prepara query para inserir dados no BD
            return $query->execute([$nome,$sobrenome,$operacao,$email]); //Executa query para inserir no BD
        }
    }
?>

<!-- create database mex;

use mex;

create table usuarios(
	id int primary key auto_increment,
    nome varchar(50) not null,
    sobrenome varchar(100) not null,
    operacao varchar(100) not null,
    email varchar(100) not null unique,
    senha varchar(100),
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ultimo_login DATETIME
);

create table chats(
	id_chat int primary key auto_increment,
    source_id varchar(100) not null,
    CorrelationId varchar(100) not null,
    MiningId varchar(100) not null,
    metadata text not null,
    subiu boolean not null,
    enviado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table nestle(
	id_nestle int primary key auto_increment,
    nome_arquivo varchar(100) not null,
    SessionId varchar(100) not null,
    CorrelationId varchar(100) not null,
    CurrentMediaLength int not null,
    TotalMediaLength int not null,
    MiningId varchar(100) not null,
    metadata text not null,
    xml text not null,
    data_pasta date not null,
    enviado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

SELECT * FROM mex.usuarios;
SELECT * FROM mex.chats;
SELECT * FROM mex.nestle; -->

