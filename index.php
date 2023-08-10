<?php
    require_once("Pessoa.php");
    $p = new Pessoa("CRUDPDO","localhost", "root", "");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        if (isset($_POST['nome'])) { //clicou na cadastrar ou no editar
            //----------------Editar----------------
            if (isset($_GET['id_up']) && !empty($id_up)) {
                $id_upd = addslashes($_GET['id_up']);
                $nome = addslashes($_POST['nome']);
                $telefone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);

                if (!empty($nome) && !empty($telefone) && !empty($email)) {
                    $p->atualizarDados($id_upd,$nome, $telefone, $email); 
                } else {
                    echo "Preencha todos os campos";
                }
            } else { // ------------------Cadastrar-----------------
                $nome = addslashes($_POST['nome']);
                $telefone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);

                if (!empty($nome) && !empty($telefone) && !empty($email)) {
                    if (!$p->cadastrarPessoa($nome, $telefone, $email)) {
                        echo "E-mail já está cadastrado";
                    }
                } else {
                    echo "Preencha todos os campos";
                }
            }
        }
    ?>
    <?php
        if (isset($_GET['id_up'])) {
            $id_update = addslashes($_GET['id_up']);
            $res = $p->buscarDadosPessoa($id_update);
        }

    ?>
    <section id="esquerda">
        <form method="POST" action="">
            <h2>Cadastrar pessoa</h2>
            <label form="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?php if (isset($res)) { echo $res['nome'];} ?>">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?php if (isset($res)) { echo $res['telefone'];} ?>">
            <label for="email">E-mail</label>
            <input type="text" name="email" id="email" value="<?php if (isset($res)) { echo $res['email'];} ?>">
            <input type="submit" value="<?php if (isset($res)) {echo 'Atualizar';}
            else {echo 'Cadastrar';} ?>">
        </form>
    </section>
    <section id="direita">
        <table>
            <tr id="titulo">
                <td>Nome</td>
                <td>Telefone</td>
                <td colspan="2">E-mail</td>
            </tr>
        <?php
            $dados = $p->buscarDados();
            if (count($dados) > 0) {
                for($i=0; $i < count($dados); $i++) {
                    echo "<tr>";
                    foreach($dados[$i] as $k => $v) {
                        if ($k != "id") {
                            echo "<td>".$v."</td>";
                        }
                    }
                    ?><td><a href="index.php?id_up=<?php echo $dados[$i]['id'];?>">Editar</a><a href="index.php?id=<?php echo $dados[$i]['id'];?>">Excluir</a></td><?php
                    echo "</tr>";
                }
               
            } else { //banco está vazio
                echo "Ainda não há pessoas cadastradas";
            }

        ?>
        </table>

    </section>
</body>
</html>



<?php
    if (isset($_GET['id'])) { 
        $id_pessoa = addslashes($_GET['id']);
        $p->excluirPessoa($id_pessoa);
        header("Location: index.php");
    }
?>