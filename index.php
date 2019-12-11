<?php
    require_once 'classes/Pessoa.php';
    $p = new Pessoa("crudpdo", "127.0.0.1", "root", "");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./styles/style.css"/>
    <title>Projeto CRUD</title>
</head>
<body>
    <?php

        if(isset($_POST['nome'])) 
        //button register was clicked or edit button
        {
            //edit
            if(isset($_GET['id_updt']) && !empty($_GET['id_updt']))
            {
                $id_updt = addslashes($_GET['id_updt']);
                $name = addslashes($_POST['nome']);
                $phone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);
                if(!empty($name) && !empty($phone) && !empty($email))
                {
                    //edit --update
                    $p->updateData($id_updt, $name, $phone, $email);
                    header("Location: index.php");
                }
            }
            //register --insert
            else
            {
                $name = addslashes($_POST['nome']);
                $phone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);
                if(!empty($name) && !empty($phone) && !empty($email))
                {
                    //register --insert
                    if(!$p->insertPerson($name, $phone, $email))
                    {
                        ?>
                            <div>
                                <img src="aviso.png"/>
                                <h4>Email já cadastrado!</h4>
                            </div>
                        <?php
                    }
                }
            }
        }
    ?>
    <?php
        if(isset($_GET['id_updt'])) //if the edit button was clicked
        {
            $id_updt = addslashes($_GET['id_updt']);
            $result = $p->searchPersonData($id_updt);
        }
    ?>
    <section id="left">
        <form method="POST">
            <h3>Cadastrar Usuário</h3>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" autofocus required
            value="<?php if(isset($result)){echo $result['nome'];} ?>"/>
            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" required
            value="<?php if(isset($result)){echo $result['telefone'];} ?>">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required 
            value="<?php if(isset($result)){echo $result['email'];} ?>"/>
            <input type="submit" value="<?php if(isset($result)){echo "Atualizar";}
                                              else{echo "Cadastrar";} ?>"/>
        </form>
    </section>
    <section id="right">
        <table>
            <tr id="title">
                <td>Nome</td>
                <td>Telefone</td>
                <td colspan="2">Email</td>
            </tr>
        <?php
            $data = $p->searchAll();
            if(count($data) > 0) //if theres already registered data
            {
                for ($i=0; $i < count($data) ; $i++)
                {
                    echo "<tr>";
                    foreach ($data[$i] as $k => $v) //k = column
                    {
                        if($k != "id")
                        {
                            echo "<td>".$v."</td>";
                        }
                    }
        ?>
            <td>
                <a href="index.php?id_updt=<?php echo $data[$i]['id']; ?>">Editar</a>
                <a href="index.php?id=<?php echo $data[$i]['id']; ?>">Excluir</a>
            </td>
        <?php
                    echo "</tr>";
                }
            }
            else //the table is empty
            {
            ?>     
        </table>
            <div class="aviso">
                <h4>Ainda não há pessoas cadastradas!</h4>
            </div>
        <?php
            }
        ?>
    </section>
</body>
</html>

<?php

    if(isset($_GET['id']))
    {
        $id_pessoa = addslashes($_GET['id']);
        $p->deletePerson($id_pessoa);
        header("Location: index.php");
    }

?>