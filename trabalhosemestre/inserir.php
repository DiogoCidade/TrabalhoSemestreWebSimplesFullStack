<?php
include 'conexao.php';

if($_POST) {
    $nome = $_POST['nome'];
    $fabricante = $_POST['fabricante'];
    $categoria = $_POST['categoria'];
    $preco = $_POST['preco'];
    $estoque = $_POST['quantidade_estoque'];
    $validade = $_POST['data_validade'];
    $controlado = isset($_POST['controlado']) ? 1 : 0;
    
    $sql = "INSERT INTO medicamentos(nome, fabricante, categoria, preco, quantidade_estoque, data_validade, controlado) 
            VALUES('$nome', '$fabricante', '$categoria', '$preco', '$estoque', '$validade', '$controlado')";
    
    if($conn->query($sql)) {
        echo "<script>alert('Medicamento cadastrado com sucesso!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar: ".$conn->error."');</script>";
    }
}
$conn->close();
?>
<head>
    <meta charset="utf-8"> <!-- apenas para a correcao de erros de grafia -->
</head>
