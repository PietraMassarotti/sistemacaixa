<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa Simples</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Caixa do Mercado</h1>

        <div class="form-buttons">           
                <a href="../Index.html" class="btn btn-primary">Sair do caixa</a>
        </div>
        
        <form action="processar_venda.php" method="post" class="caixa-form">
            <div class="form-group">
                <label for="produto">Produto:</label>
                <select name="produto_id" id="produto" required>
                    <option value="">Selecione um produto</option>
                    <?php
                    include 'config.php';                                                                               
                    $stmt = $pdo->query('SELECT id, nome, preco FROM produtos_tbl');
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>" data-preco="<?= htmlspecialchars($row['preco']) ?>">
                            <?= htmlspecialchars($row['nome']) ?> - R$ <?= htmlspecialchars($row['preco']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" name="quantidade" id="quantidade" value="1" min="1" required>
            </div>

            <button type="submit" class="btn btn-success">Adicionar ao Caixa</button>

        </form>

        <h2>Caixa Atual</h2>
        <table class="caixa-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                session_start();
                $total = 0;
                if (isset($_SESSION['itens']) && !empty($_SESSION['itens'])):
                    foreach ($_SESSION['itens'] as $item):
                        $subtotal = $item['preco'] * $item['quantidade'];
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td><?= htmlspecialchars($item['quantidade']) ?></td>
                            <td>R$ <?= htmlspecialchars($item['preco']) ?></td>
                            <td>R$ <?= htmlspecialchars($subtotal) ?></td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="4">Nenhum item adicionado ao caixa.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td>R$ <?= htmlspecialchars(number_format($total, 2, ',', '.')) ?></td>
                </tr>
            </tfoot>
        </table>

        <form action="processar_venda.php" method="post">
            <input type="hidden" name="finalizar_venda" value="true">
            <button type="submit" class="btn btn-success" <?= ($total > 0) ? '' : 'disabled' ?>>Finalizar Venda</button>
            <a href="processar_venda.php?cancelar_venda=true" class="btn btn-danger" <?= ($total > 0) ? '' : 'disabled' ?>>Cancelar Venda</a>
        </form>
        
    </div>
   
</body>
</html>
