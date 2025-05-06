function confirmarExclusao(nomeProduto) {
    return confirm("Tem certeza que deseja excluir o produto: " + nomeProduto + "?");
}

function validarFormulario() {
    let nome = document.getElementById("nome").value;
    let quantidade = document.getElementById("quantidade").value;
    let preco = document.getElementById("preco").value;

    if (nome === "" || quantidade === "" || preco === "") {
        alert("Por favor, preencha todos os campos.");
        return false; 
    }

    if (isNaN(quantidade) || isNaN(preco)) {
        alert("Quantidade e preço devem ser números.");
        return false;
    }

    return true; 
}
