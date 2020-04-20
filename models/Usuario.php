<?php
    include_once 'Conexao.php';
    
    class Usuario extends Conexao{
        // Metodo para cadastrar novo usuario
        public function cadastrarUsuario($nome,$sobrenome,$operacao,$email){
            $db = parent::criarConexao(); //Sintaxe para chamar um metodo da classe pai
            $query = $db->prepare("INSERT INTO usuarios (nome,sobrenome,operacao,email) VALUES (?,?,?,?)"); //Prepara query para inserir dados no BD
            return $query->execute([$nome,$sobrenome,$operacao,$email]); //Executa query para inserir no BD
        }

        // Metodo para pegar dados do usuario
        public function recuperaUsuario($email){
            $db = parent::criarConexao(); //Sintaxe para chamar um metodo da classe pai
            $query = $db->prepare("SELECT * FROM usuarios WHERE email = :email"); //Prepara e executa query para pegar todos os dados do usuario
            $query->bindValue(":email", $email);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_OBJ); //Transforma dados do BD em um array de Objetos
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
    }
?>

<!-- create database mex;

drop database mex;

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

drop table usuarios;

INSERT INTO usuarios (nome,sobrenome,operacao,email) VALUES ("Monica","Craveiro de Menezes","Mex","monica.craveiro@mexconsulting.com.br");

select * from usuarios; -->

