<?php
include 'conexao.php';

if(!isset($_GET['id'])) {
    die("ID inválido.");
}

$id = (int) $_GET['id'];

$sql = "SELECT nome FROM medicamentos WHERE id = $id";
$result = $conn->query($sql);

if(!$result || $result->num_rows == 0) {
    die("Medicamento não encontrado.");
}

$medicamento = $result->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql_delete = "DELETE FROM medicamentos WHERE id = $id";
    
    if($conn->query($sql_delete)) {
        echo "<script>alert('Medicamento excluído com sucesso!'); window.location='index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Erro ao excluir: ".$conn->error."');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Excluir Medicamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-danger">
        <h4>Confirmação de Exclusão</h4>
        <p><strong>Tem certeza que deseja excluir o medicamento:</strong></p>
        <h5><?php echo $medicamento['nome']; ?></h5>
        <p class="text-danger">Esta ação não pode ser desfeita!</p>
    </div>
    
    <form method="POST">
        <button type="submit" class="btn btn-danger">Sim, Excluir</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
<?php $conn->close(); ?>
