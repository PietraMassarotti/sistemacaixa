# sitemacaixa
 Sistema de caixa para um mercado, com sistema de gerenciamento de estoque, além do proprio caixa, usamos php, html, javascript,css e o mysql

Sistema de Caixa de Supermercado
Autor: Jonas Maia, João Vitor, Vitor Kalel, Rômulo Guilherme
Versão: PHP 8.1+ | MySQL 5.7+ | JavaScript ES6+ 
Data: Início: 17/03/25 , Termínio: 30/03/25

1. Visão Geral
Este documento descreve o funcionamento do sistema de gerenciamento de caixa (vendas) e estoque de um supermercado. O sistema é composto pelos seguintes módulos:
•	Página Inicial: index.php - Redireciona para os módulos.
•	Módulo Caixa: caixa.php - Responsável pelo registro de vendas.
•	Módulo Estoque: estoque.php - Responsável pela gestão de produtos.

2. Estrutura do Banco de Dados
2.1. Tabelas Principais
A base de dados é composta pelas seguintes tabelas:
CREATE TABLE `produtos_tbl` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `quantidade` int NOT NULL,
  `preco` float NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
 UNIQUE KEY `nome_UNIQUE` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3; 
3. Backend (PHP)
3.1. Arquivos Críticos
•	config.php: Configuração da conexão com o banco de dados MySQL.
•	Caixa.php: Faz as seguintes funções:                                                                                                             
•	 ○Exibe um formulário para selecionar produtos.
○ Adiciona produtos ao carrinho (sessão) e exibe uma tabela com os itens.
○ Calcula e exibe o total da compra.
○ Permite finalizar a venda ou cancelá-la.
•	Processar_venda.php: Implementa a lógica para:
o	Adiciona produtos ao carrinho (verificando se há estoque)..
o	Se o produto já estiver no carrinho, aumenta a quantidade.
o	Se o estoque for insuficiente, exibe um alerta.
o	Permite finalizar a venda, reduzindo o estoque no banco.
o	Se a finalização falhar, reverte as alterações.
o	Permite cancelar a venda, limpando o carrinho.
•	estoque.php: Realiza o CRUD (Create, Read, Update, Delete) de produtos.
4. Frontend (HTML/Css/JavaScript)
4.1. Componentes Principais
Módulo Caixa:
•	Formulário dinâmico para adição de itens via JavaScript.
•	Evento onclick para cálculo de troco em tempo real.
Módulo Estoque:
•	Tabela de produtos com busca dinâmica utilizando o evento keyup.

5. Instalação
5.1. Requisitos
•	PHP 7.4 ou superior.
•	MySQL 5.7 ou superior.
5.2. Passos de Instalação
# 1. Importar o esquema do banco de dados
mysql -u usuario -p database < schema.sql

# 2. Configurar a conexão no arquivo includes/conexao.php

6. Melhorias Sugeridas
6.1. Segurança
•	Implementação de autenticação com password_hash().
6.2. Funcionalidades
•	Geração de cupom fiscal em PDF.
•	Relatório de vendas diárias.
•	Calcular o troco.
6.3. Performance
•	Implementação de paginação na gestão de estoque para otimizar consultas com um grande número de produtos.

Documentação: CRUD de Estoque
Descrição
Este sistema permite a gestão de um estoque de produtos, permitindo adicionar, editar e excluir produtos. Ele está implementado em PHP, HTML e CSS, com um banco de dados MySQL para armazenar as informações dos produtos.
Arquitetura
A aplicação é dividida em duas partes principais:
1.	Formulário de Cadastro/Atualização de Produtos: Permite ao usuário adicionar ou editar os produtos.
2.	Lista de Produtos: Exibe os produtos cadastrados no banco de dados com as opções de editar ou excluir.
Banco de Dados
A aplicação interage com uma tabela chamada produtos_tbl no banco de dados estoque. A estrutura da tabela é a seguinte:
•	id (INT, AUTO_INCREMENT): Identificador único do produto.
•	nome (VARCHAR): Nome do produto.
•	quantidade (INT): Quantidade disponível do produto no estoque.
•	preco (DECIMAL): Preço do produto.
Funcionalidades
1. Conexão com o Banco de Dados
O código se conecta ao banco de dados MySQL usando PDO. Caso a conexão falhe, uma mensagem de erro é exibida.
php
Copiar
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
2. Adicionar Produto
Quando um usuário preenche o formulário com os dados do produto (nome, quantidade e preço) e envia o formulário, um novo registro é inserido na tabela produtos_tbl.
php
Copiar
$sql = "INSERT INTO produtos_tbl (nome, quantidade, preco) VALUES (:nome, :quantidade, :preco)";
$stmt = $pdo->prepare($sql);
$stmt->execute(['nome' => $nome, 'quantidade' => $quantidade, 'preco' => $preco]);
3. Editar Produto
Quando um usuário clica no botão "Editar", o sistema preenche o formulário com os dados do produto selecionado para que ele possa ser modificado. Após a alteração, os dados são atualizados no banco de dados.
php
Copiar
$sql = "UPDATE produtos_tbl SET nome = :nome, quantidade = :quantidade, preco = :preco WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['nome' => $nome, 'quantidade' => $quantidade, 'preco' => $preco, 'id' => $id]);
4. Excluir Produto
Quando o usuário clica em "Excluir", um formulário POST é enviado com a ação delete, que exclui o produto da tabela.
php
Copiar
$sql = "DELETE FROM produtos_tbl WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
5. Exibição da Lista de Produtos
Os produtos cadastrados são exibidos em uma tabela HTML. Para cada produto, são exibidos o ID, nome, quantidade, preço e as opções de editar e excluir.
php
Copiar
$stmt = $pdo->query('SELECT * FROM produtos_tbl');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
6. Validação e Cancelamento
O formulário de cadastro e atualização possui uma função JavaScript para validar o preenchimento dos campos. Além disso, se o usuário estiver editando um produto, pode cancelar a edição e retornar à lista de produtos.
HTML e CSS
A interface foi desenvolvida com HTML e CSS para fornecer um design simples e funcional para a aplicação. O CSS é utilizado para estruturar e estilizar os elementos da página, como formulários, tabelas e botões.
Fluxo de Funcionamento
1.	Adicionar Produto:
o	O usuário preenche os campos de nome, quantidade e preço.
o	Ao clicar no botão "Salvar", um novo produto é adicionado ao banco de dados.
2.	Editar Produto:
o	O usuário clica no botão "Editar" ao lado de um produto na lista.
o	O sistema preenche o formulário com os dados do produto selecionado.
o	O usuário pode modificar os campos e clicar em "Atualizar" para salvar as alterações.
3.	Excluir Produto:
o	O usuário clica em "Excluir" ao lado do produto.
o	O produto é removido do banco de dados após a confirmação.
________________________________________
Possíveis Melhorias e Considerações
•	Validação de Dados: A validação no lado do cliente poderia ser expandida para garantir que os dados inseridos no formulário sejam válidos, como checar se o preço e a quantidade são números positivos.
•	Segurança: É importante aplicar medidas de segurança, como o uso de prepared statements para evitar SQL injection, o que já está sendo feito corretamente.
•	Feedback do Usuário: A aplicação poderia fornecer feedback visual mais claro, como alertas em cores diferentes para diferentes tipos de ações (sucesso, erro).
•	Responsividade: O layout poderia ser aprimorado para ser responsivo e funcionar bem em dispositivos móveis.
