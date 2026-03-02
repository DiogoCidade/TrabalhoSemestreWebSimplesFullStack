<?php
include 'conexao.php';

$id = $_GET['id'];
$sql = "SELECT * FROM medicamentos WHERE id = $id";
$result = $conn->query($sql);
$medicamento = $result->fetch_assoc();

if($_POST) {
    $nome = $_POST['nome'];
    $fabricante = $_POST['fabricante'];
    $categoria = $_POST['categoria'];
    $preco = $_POST['preco'];
    $estoque = $_POST['quantidade_estoque'];
    $validade = $_POST['data_validade'];
    $controlado = isset($_POST['controlado']) ? 1 : 0;
    
    $sql_update = "UPDATE medicamentos SET nome='$nome', fabricante='$fabricante', categoria='$categoria', 
                   preco='$preco', quantidade_estoque='$estoque', data_validade='$validade', controlado='$controlado' 
                   WHERE id=$id";
    
    if($conn->query($sql_update)) {
        echo "<script>alert('Medicamento atualizado!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar: ".$conn->error."');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Editar Medicamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Medicamento</h2>
    <form method="POST" id="formMedicamento">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="mb-3">
            <label class="form-label">Nome *</label>
            <input type="text" name="nome" id="nome" value="<?php echo $medicamento['nome']; ?>" class="form-control" required minlength="3">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Fabricante *</label>
            <input type="text" name="fabricante" value="<?php echo $medicamento['fabricante']; ?>" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Categoria *</label>
            <select name="categoria" class="form-control" required>
                <option value="antibiotico" <?php echo $medicamento['categoria']=='antibiotico'?'selected':'';?>>Antibiótico</option>
                <option value="analgesico" <?php echo $medicamento['categoria']=='analgesico'?'selected':'';?>>Analgésico</option>
                <option value="antiinflamatorio" <?php echo $medicamento['categoria']=='antiinflamatorio'?'selected':'';?>>Anti-inflamatório</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Preço *</label>
            <input type="number" name="preco" value="<?php echo $medicamento['preco']; ?>" step="0.01" min="0.01" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Estoque *</label>
            <input type="number" name="quantidade_estoque" value="<?php echo $medicamento['quantidade_estoque']; ?>" min="0" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Validade *</label>
            <input type="date" name="data_validade" id="data_validade" value="<?php echo $medicamento['data_validade']; ?>" class="form-control" required>
        </div>
        
        <div class="mb-3 form-check">
            <input type="checkbox" name="controlado" <?php echo $medicamento['controlado']==1?'checked':'';?> class="form-check-input" id="controlado">
            <label class="form-check-label" for="controlado">Controlado</label>
        </div>
        
        <button type="submit" class="btn btn-warning">Atualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
function validarForm() {
    var nome = document.getElementById('nome').value;
    var dataValidade = document.getElementById('data_validade').value;
    var hoje = new Date().toISOString().split('T')[0];
    
    if(nome.length < 3) {
        alert('Nome deve ter pelo menos 3 caracteres!');
        return false;
    }
    
    if(dataValidade <= hoje) {
        alert('Data de validade deve ser posterior à data atual!');
        return false;
    }
    
    return true;
}
document.getElementById('formMedicamento').onsubmit = validarForm;
</script>
</body>
</html>
<?php $conn->close(); ?>
