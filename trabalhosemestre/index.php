<?php
include 'conexao.php';

$busca = "";
$sql = "SELECT * FROM medicamentos";

if(isset($_GET['busca']) && $_GET['busca'] != "") {
    $busca = $_GET['busca'];
    $busca_escapada = $conn->real_escape_string($busca);
    $sql .= " WHERE nome LIKE '%$busca_escapada%' OR fabricante LIKE '%$busca_escapada%'";
}

$sql .= " ORDER BY nome ASC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Farmácia - Controle de Medicamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fb;
        }
        .top-bar {
            background: linear-gradient(90deg, #0d6efd, #6610f2);
            color: #fff;
            padding: 20px 0;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .top-bar h1 {
            font-size: 26px;
            margin: 0;
        }
        .top-bar small {
            font-size: 13px;
            opacity: 0.9;
        }
        .card-main {
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            border: none;
        }
        .badge-estoque-baixo {
            background-color: #ffc107;
        }
        .badge-estoque-ok {
            background-color: #198754;
        }
        .linha-vencido {
            background-color: #ffe5e5 !important;
        }
        .linha-perto-vencer {
            background-color: #fff7e0 !important;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h1>Controle de Medicamentos</h1>
                <small>Sistema simples de estoque para farmácia</small>
            </div>
            <div>
                <a href="inserir.html" class="btn btn-light btn-sm">+ Novo medicamento</a>
            </div>
        </div>
    </div>

    <div class="container mb-4">
        <div class="card card-main">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><strong>Medicamentos cadastrados</strong></span>
                <form class="d-flex" method="get" action="index.php" style="max-width: 320px;">
                    <input class="form-control form-control-sm me-2" type="text" name="busca"
                           placeholder="Buscar por nome ou fabricante"
                           value="<?php echo htmlspecialchars($busca); ?>">
                    <button class="btn btn-outline-primary btn-sm" type="submit">Buscar</button>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Fabricante</th>
                                <th>Categoria</th>
                                <th>Preço</th>
                                <th>Estoque</th>
                                <th>Validade</th>
                                <th>Controlado</th>
                                <th style="width: 130px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        date_default_timezone_set('America/Sao_Paulo');
                        $hoje = date('Y-m-d');

                        if($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {

                                $classeLinha = '';
                                $dataValidade = $row['data_validade'];
                                $diferenca = (strtotime($dataValidade) - strtotime($hoje)) / (60 * 60 * 24);

                                if($dataValidade < $hoje) {
                                    $classeLinha = 'linha-vencido';
                                } elseif($diferenca <= 30) {
                                    $classeLinha = 'linha-perto-vencer';
                                }

                                echo "<tr class='{$classeLinha}'>";
                                echo "<td>".$row['id']."</td>";
                                echo "<td>".$row['nome']."</td>";
                                echo "<td>".$row['fabricante']."</td>";
                                echo "<td>".$row['categoria']."</td>";
                                echo "<td>R$ ".number_format($row['preco'],2,',','.')."</td>";

                                $badgeClass = ($row['quantidade_estoque'] <= 5) ? 'badge-estoque-baixo' : 'badge-estoque-ok';
                                echo "<td><span class='badge ".$badgeClass."'>".$row['quantidade_estoque']."</span></td>";

                                echo "<td>".date('d/m/Y', strtotime($row['data_validade']))."</td>";
                                echo "<td>".($row['controlado'] == 1 ? 'Sim' : 'Não')."</td>";
                                echo "<td>
                                        <a href='editar.php?id=".$row['id']."' class='btn btn-warning btn-sm'>Editar</a>
                                        <a href='excluir.php?id=".$row['id']."' class='btn btn-danger btn-sm'>Excluir</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center p-3'>Nenhum medicamento cadastrado.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted small">
                <span>Linhas em amarelo: validade em até 30 dias | Linhas em vermelho: medicamento vencido</span>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
