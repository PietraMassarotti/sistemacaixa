<?php
session_start();
include 'config.php';

$erro = "";

if (stripos($_POST['quantidade'], 'e') !== false) {
    $erro = "Digite apenas números no campo 'Quantidade'";
    header("Location: caixa.php?erro=$erro");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['finalizar_venda']) && empty($erro)) {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    $stmt = $pdo->prepare('SELECT id, nome, preco, quantidade as estoque FROM produtos_tbl WHERE id = :id');
    $stmt->execute(['id' => $produto_id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        if ($produto['estoque'] >= $quantidade) {

            if (!isset($_SESSION['itens'])) {
                $_SESSION['itens'] = [];
            }

            $produto_existente = false;
            foreach ($_SESSION['itens'] as $key => $item) {
                if ($item['id'] == $produto_id) {
                    $_SESSION['itens'][$key]['quantidade'] += $quantidade;
                    $produto_existente = true;
                    break;
                }
            }

            if (!$produto_existente) {
                $_SESSION['itens'][] = [
                    'id' => $produto['id'],
                    'nome' => $produto['nome'],
                    'preco' => $produto['preco'],
                    'quantidade' => $quantidade
                ];
            }
        } else {
            $erro = 'Estoque insuficiente para este produto.';
            echo "<script>alert($erro); window.location.href='caixa.php?erro=$erro';</script>";
            exit;
        }
    }
} elseif (!empty($erro)) {
    header("Location: caixa.php?erro=$erro");
    echo "<script>alert('$erro'); window.location.href='caixa.php?erro=$erro';</script>";
    exit;
}




if (isset($_POST['finalizar_venda'])) {
    if (isset($_SESSION['itens']) && !empty($_SESSION['itens'])) {
        try {
            $pdo->beginTransaction();

            foreach ($_SESSION['itens'] as $item) {

                $sql = "UPDATE produtos_tbl SET quantidade = quantidade - :quantidade WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['quantidade' => $item['quantidade'], 'id' => $item['id']]);

                if ($stmt->rowCount() == 0) {
                    throw new Exception("Erro ao atualizar o estoque do produto: " . $item['nome']);
                }
            }

            $pdo->commit();
            unset($_SESSION['itens']);
            echo "<script>alert('Venda finalizada com sucesso!'); window.location.href='caixa.php?erro=$erro';</script>";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Erro ao finalizar a venda: " . $e->getMessage() . "'); window.location.href='caixa.php?erro=$erro';</script>";
        }
    }
}

if (isset($_GET['cancelar_venda'])) {
    unset($_SESSION['itens']);
    header("Location: caixa.php?erro=$erro");
    exit;
}

header("Location: caixa.php?erro=$erro");
exit;
