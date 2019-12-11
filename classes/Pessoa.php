<?php

    Class Pessoa
    {

        private $pdo;

        public function __construct($dbname, $host, $user, $passwd)
        {
            try
            {
                $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$passwd);
            } catch (PDOException $pdoex) 
            {
                echo "Erro com o banco de dados: " . $pdoex->getMessage();
                exit();
            }
            catch(Exception $ex)
            {
                echo "Erro genérico: " . $ex->getMessage();
                exit();
            }
        }

        //select all the recorded data
        public function searchAll()
        {
            $result = array();
            $cmd = $this->pdo->query(" SELECT * FROM pessoa ORDER BY nome ");
            $result = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        //create -- insert
        public function insertPerson($name, $phone, $email)
        {
            //verifies if theres already an email registered with the same name
            $cmd = $this->pdo->prepare(" SELECT id FROM pessoa WHERE email = :e ");
            $cmd->bindValue(":e", $email);
            $cmd->execute();
            if($cmd->rowCount() > 0 ) //email already exists
            {
                return false;
            }
            else
            {
                $cmd = $this->pdo->prepare(" INSERT INTO pessoa(nome, telefone, email) VALUES (:n, :p, :e) ");
                $cmd->bindValue(":n", $name);
                $cmd->bindValue(":p", $phone);
                $cmd->bindValue(":e", $email);
                $cmd->execute();
                return true;
            }
        }

        //delete
        public function deletePerson($id)
        {
            $cmd = $this->pdo->prepare(" DELETE FROM pessoa WHERE id = :id ");
            $cmd->bindValue(":id", $id);
            $cmd->execute();
        }

        //searches a person data
        public function searchPersonData($id)
        {
            $result = array();
            $cmd = $this->pdo->prepare(" SELECT * FROM pessoa WHERE id = :id ");
            $cmd->bindValue(":id", $id);
            $cmd->execute();
            $result = $cmd->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        //updates data at the db
        public function updateData($id, $name, $phone, $email)
        {
            $cmd = $this->pdo->prepare(" UPDATE pessoa SET nome = :nome, telefone = :telefone, 
                                    email = :email WHERE id = :id ");
            $cmd->bindValue(":nome", $name);
            $cmd->bindValue(":telefone", $phone);
            $cmd->bindValue(":email", $email);
            $cmd->bindValue(":id", $id);
            $cmd->execute();
        }
    }
?>