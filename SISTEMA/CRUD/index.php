<?php
require("../CAIXA/config.php");

$erro="";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET["id"])) {
        header("Location: estoque.php");
        exit;
    }

    $id = $_GET["id"];
    $stmt = $pdo->prepare("SELECT * FROM produtos_tbl WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        header("Location: estoque.php");
        exit;
    }

    $nome = $produto['nome'];
    $quantidade = $produto['quantidade'];
    $preco = $produto['preco'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["id"], $_POST["nome"], $_POST["quantidade"], $_POST["preco"])) {
        $id = $_POST["id"];
        $nome = $_POST["nome"];
        $quantidade = $_POST["quantidade"];
        $preco = $_POST["preco"];

        if (!preg_match("/^[a-zA-Z0-9 àáâãèéêìíîòóôõùúûçÀÁÂÃÈÉÊÌÍÎÒÓÔÕÙÚÛÇ ]*$/", $nome)) {
            $erro = "Não use caracteres especiais!";
        } else {
            if (stripos($quantidade, 'e') == true || stripos($preco, 'e') == true) {
                $erro = "Digite apenas números no campo 'Quantidade' e 'Preço'";
            } else {
                $preco = str_replace(',', '.', $preco);


                $stmt = $pdo->prepare("UPDATE produtos_tbl SET nome = :nome, quantidade = :quantidade, preco = :preco WHERE id = :id");
                $stmt->execute([
                    'id' => $id,
                    'nome' => $nome,
                    'quantidade' => $quantidade,
                    'preco' => $preco
                ]);

                header("Location: estoque.php");
                exit;
            }
        }
    } else {
        echo "Dados incompletos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Atualizar Produto</title>
</head>
<link rel="stylesheet" href="style.css">

<body>
    <div class="container">
        <h2>Atualizar Produto</h2>
        <section class="form-section">
            <form method="post" class="product-form">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>" required>
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required><br>
                </div>

                <div class="form-group">
                    <label>Quantidade:</label>
                    <input type="text" name="quantidade" value="<?php echo htmlspecialchars($quantidade); ?>" required><br>
                </div>

                <div class="form-group">
                    <label>Preço:</label>
                    <input type="text" name="preco" value="<?php echo htmlspecialchars($preco); ?>" required><br>
                </div>

                <?php
                echo $erro;
                ?>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                    <a href="estoque.php" class="btn btn-secondary">Cancelar</a>
                </div>

            </form>
        </section>
    </div>

</body>

</html>