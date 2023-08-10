<?php

class Pessoa {
    private $pdo;



    //Conexão com o banco
    public function __construct($dbname, $host, $user, $senha) {
        try {
        $this->pdo = new PDO("mysql:dbname=" .$dbname. ";host=".$host, $user, $senha);
        }
        catch(PDOExpection $e) {
            echo "Erro com o banco de dados: " .$e->getMessage(); 
            exit();
        }
        catch(Exception $e) {
            echo "Erro genérico: " .$e-getMessage();
            exit(); 
        }
    }

    //Funcao para buscar os dados
    public function buscarDados() {
        $res = array();
        $cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY id DESC");
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    //Funcao de cadastrar usuario
    public function cadastrarPessoa($nome, $telefone, $email) {
        //antes de cadastrar verificar se existe o email

        $cmd = $this->pdo->prepare("SELECT id FROM pessoa WHERE email = :e");
        $cmd->bindValue(":e", $email);
        $cmd->execute();
        if ($cmd->rowCount() > 0) { //Já existe no banco
            return false;
        } else { //não foi encontrado
            $cmd = $this->pdo->prepare("INSERT INTO pessoa (nome,telefone,email) VALUES (:n, :t, :e)");
            $cmd->bindValue(":n", $nome);
            $cmd->bindValue(":t", $telefone);
            $cmd->bindValue(":e", $email);
            $cmd->execute();
            return true;
        }
    }

    public function excluirPessoa($id) {
        $cmd = $this->pdo->prepare("DELETE FROM pessoa WHERE id = :id");
        $cmd->bindValue(":id", $id);
        $cmd->execute();
    }

    //Buscar os dados de uma pessoa espeficica
    public function buscarDadosPessoa($id) {
        $res = array();
        $cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
        $cmd->bindValue(":id", $id);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    //Update no banco de dados
    public function atualizarDados($id, $nome, $telefone, $email) {
        $cmd = $this->pdo->prepare("UPDATE pessoa SET nome = :n, telefone = :t, email=:e WHERE id= :id");
        $cmd->bindValue(":n", $nome);
        $cmd->bindValue(":t", $telefone);
        $cmd->bindValue(":e", $email);
        $cmd->bindValue(":id", $id);
        $cmd->execute();
    }

}